<?php
/*
    All code copyright (c) 2024 by Duane Storey - All rights reserved
    You may use, distribute and modify this code under the terms of GPL version 3.0 license or later
*/

namespace CR;

class Builder {
    var $config = null;
    var $totalPages = 0;

    var $templateEngine = null;
    var $entries = null;
    var $theme = null;
    var $menu = null;
    var $pluginManager = null;
    var $renderer = null;

    public function __construct( $config ) {
        $this->config = $config;

        $this->templateEngine = new TemplateEngine( $config );
        $this->templateEngine->setTemplateDir( CROSSROADS_BASE_DIR . '/' . $this->config->get( 'dirs.themes', 'core/themes' ) . '/' . $config->get( 'site.theme' ) );

        $this->pluginManager = new PluginManager( $this->config );
        $this->pluginManager->installPlugin( new ImagePlugin( $this->config ) );
        $this->pluginManager->installPlugin( new SeoPlugin( $this->config ) );
        $this->pluginManager->installPlugin( new WordPressPlugin( $this->config ) );
    }

    public function run() {
        @mkdir( CROSSROADS_PUBLIC_DIR );
        @mkdir( CROSSROADS_PUBLIC_DIR . '/assets' );

        $this->_setupTheme();
        $this->_setupMenus();

        $this->renderer = new Renderer( $this->config, $this->templateEngine, $this->pluginManager, $this->menu, $this->theme );

        // load all content here
        $this->entries = new Entries( $this->config );
        $this->entries->loadAll();

        // do all content filtering
        $all_entries = [];

        foreach( $this->config->get( 'content', [] ) as $contentType => $contentConfig ) {
            $entries = $this->entries->get( $contentType );

            if ( count( $entries ) ) {
                LOG( sprintf( _i18n( 'core.build.plugins.processing' ), $contentType ), 1, LOG::INFO );

                $entries = $this->pluginManager->processAll( $entries );
                $all_entries = array_merge( $all_entries, $entries );
            }
        }

        foreach( $this->config->get( 'content', [] ) as $contentType => $contentConfig ) {
            $entries = $this->entries->get( $contentType );

            if ( $entries ) {
                LOG( sprintf( _i18n( 'core.build.generating.single' ), $contentType ), 1, LOG::INFO );

                // Make the output directory for html
                Utils::mkdir( CROSSROADS_PUBLIC_DIR . '/' . $contentType );

                // Make the output directory for images
                $image_destination_path = CROSSROADS_PUBLIC_DIR . '/assets/' . $contentType;
                Utils::mkdir( $image_destination_path );

                // Where the source content is
                $content_directory = \CROSSROADS_BASE_DIR . '/content/' . $contentType;
                
                foreach( $entries as $entry ) {
                    LOG( "Writing content for [" . $entry->relUrl . "]", 2, LOG::DEBUG );

                    $this->renderer->renderSinglePage( $entry, [ $entry->contentType . '-single', $entry->contentType, 'index' ] );
                    $this->totalPages++;                }
            }

            // process all content
            usort( $entries, 'CR\cr_sort' );

            if ( isset( $contentConfig[ 'index' ] ) && $contentConfig[ 'index' ] ) {
                LOG( sprintf( _i18n( 'core.build.generating.index' ), $contentType ), 1, LOG::INFO );

                $this->totalPages += $this->renderer->renderIndexPage( $entries, $contentType, '/' . $contentType, [ 'index' ] );
            }

            if ( $contentType == $this->config->get( 'site.home' ) ) {
                LOG( sprintf( _i18n( 'core.build.generating.home' ), $contentType ), 1, LOG::INFO );

                $this->totalPages += $this->renderer->renderIndexPage( $entries, $contentType, '', [ 'index' ] );
            }

                // tax
            $taxTerms = $this->entries->getTaxTerms( $contentType );
            sort( $taxTerms );
            if ( count( $taxTerms ) ) {
                LOG( sprintf( _i18n( 'core.build.generating.tax' ), $contentType ), 1, LOG::INFO );

                Utils::mkdir( CROSSROADS_PUBLIC_DIR . '/' . $contentType . '/taxonomy' );

                foreach( $taxTerms as $term ) {
                    Utils::mkdir( CROSSROADS_PUBLIC_DIR . '/' . $contentType . '/taxonomy/' . $term );

                    LOG( sprintf( _i18n( 'core.build.generating.tax' ), $contentType . "/" . $term ), 2, LOG::DEBUG );

                    $entries = $this->entries->getTax( $contentType, $term );

                    usort( $entries, 'CR\cr_sort' );

                    if ( count( $entries ) ) {
                        $this->totalPages += $this->renderer->renderIndexPage( $entries, $contentType, '/' . $contentType . '/taxonomy/' . $term, [ 'index' ] );
                    }
                }
            }
        }

        $this->_writeRobots();
        $this->_writeSitemapXml();
        $this->_write404Page();

        LOG( sprintf( _i18n( 'core.build.total' ), $this->entries->getEntryCount(), $this->totalPages ), 0, LOG::INFO );

        LOG( _i18n( 'core.build.done' ), 0, LOG::INFO );
    }

    private function _writeSitemapXml() {
        $sitemapXml  = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $sitemapXml .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

        foreach( $this->config->get( 'content', [] ) as $contentType => $contentConfig ) {
            $entries = $this->entries->get( $contentType );

            usort( $entries, 'CR\cr_sort' );

            foreach( $entries as $entry ) {
                $sitemapXml = $this->_addSitemapEntry( $sitemapXml, $entry->url );
            }

            $taxTerms = $this->entries->getTaxTerms( $contentType );
            if ( count( $taxTerms ) ) {
                $taxUrl = $this->config->get( 'site.url' ) . '/' . $contentType . '/taxonomy';
                foreach( $taxTerms as $term ) {
                    $sitemapXml = $this->_addSitemapEntry( $sitemapXml, $taxUrl . '/' . $term, 'monthly' );
                }
            }
        }

        $sitemapXml = $this->_addSitemapEntry( $sitemapXml, $this->config->get( 'site.url '), 'daily' );

        $sitemapXml .= "</urlset>\n";

        file_put_contents( CROSSROADS_PUBLIC_DIR . '/sitemap.xml', $sitemapXml );

        LOG( sprintf( _i18n( 'core.build.writing' ), "sitemap.xml" ), 1, LOG::INFO );
    }

    private function _addSitemapEntry( $sitemapXml, $url, $freq = 'weekly' ) {
        $sitemapXml .= "\t<url>\n";
        $sitemapXml .= "\t\t<loc>" . $url . "</loc>\n";
        $sitemapXml .= "\t\t<changefreq>" . $freq . "</changefreq>\n";
        $sitemapXml .= "\t</url>\n";

        return $sitemapXml;
    }

    private function _writeRobots() {
        // write robots
        $robots = "user-agent: *\ndisallow: /assets/css/\ndisallow: /assets/js/\nallow: /\n\nUser-agent: Twitterbot\nallow: /\nSitemap: " . $this->config->get( 'site.url ') . "/sitemap.xml";
        file_put_contents( CROSSROADS_PUBLIC_DIR . '/robots.txt', $robots );

        LOG( sprintf( _i18n( 'core.build.writing' ), "robots.txt" ), 1, LOG::INFO );
    }

    public function _write404Page() {
        $this->renderer->render404Page();

        LOG( sprintf( _i18n( 'core.build.writing' ), "404.html" ), 1, LOG::INFO );
    }

    private function _setupMenus() {
        $this->menu = new Menu();
        $this->menu->loadMenus();
    }

    private function _setupTheme() {
        $this->theme = new Theme( $this->config->get( 'site.theme' ), CROSSROADS_BASE_DIR . '/' . $this->config->get( 'dirs.themes', 'core/themes' ) );

        LOG( sprintf( _i18n( "core.theme.load" ), $this->theme->name() ), 1, LOG::INFO );

        if ( !$this->theme->isSane() ) {
            throw new ThemeException( 'Broken theme' );
        }

        $this->theme->loadConfig();
        LOG( _i18n( "core.theme.loaded" ), 2, LOG::INFO );

        $this->theme->processAssets( CROSSROADS_PUBLIC_DIR . '/assets' );
    }
}