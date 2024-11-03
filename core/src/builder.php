<?php
/*
    All code copyright (c) 2024 by Duane Storey - All rights reserved
    You may use, distribute and modify this code under the terms of GPL version 3.0 license or later
*/

namespace CR;

class Builder {
    var $config = null;
    var $startTime = null;
    var $totalPages = 0;
    var $templateEngine = null;
    var $entries = null;
    var $theme = null;
    var $menu = null;
    var $pluginManager = null;
    var $renderer = null;

    public function __construct( $config ) {
        $this->config = $config;

        $this->templateEngine = new TemplateEngine();
        $this->templateEngine->setTemplateDir( CROSSROAD_THEME_DIR . '/' . $config[ 'site' ][ 'theme' ] );

        $this->pluginManager = new PluginManager( $this->config );
        $this->pluginManager->installPlugin( new ImagePlugin( $this->config ) );
        $this->pluginManager->installPlugin( new SeoPlugin( $this->config ) );
        $this->pluginManager->installPlugin( new WordPressPlugin( $this->config ) );
    }

    public function run() {
        @mkdir( CROSSROAD_PUBLIC_DIR );
        @mkdir( CROSSROAD_PUBLIC_DIR . '/assets' );

        $this->_setupTheme();
        $this->_setupMenus();

        $this->renderer = new Renderer( $this->config, $this->templateEngine, $this->pluginManager, $this->menu, $this->theme );

        $this->startTime = microtime( true );

        // load all content here
        $this->entries = new Entries( $this->config, );
        $this->entries->loadAll();

        // do all content filtering
        $all_entries = $this->entries->getAll();
        foreach( $all_entries as $entry ) {
             $entry = $this->pluginManager->contentFilter( $entry );
        }

        // build
        echo "..starting template building\n";
        if ( isset( $this->config[ 'content' ][ 'types' ] ) ) {
            foreach( $this->config[ 'content' ][ 'types' ] as $contentType => $contentConfig ) {
                $entries = $this->entries->get( $contentType );

                if ( $entries ) {
                    // Make the output directory for html
                    Utils::mkdir( CROSSROAD_PUBLIC_DIR . '/' . $contentType );

                    // Make the output directory for images
                    $image_destination_path = CROSSROAD_PUBLIC_DIR . '/assets/' . $contentType;
                    Utils::mkdir( $image_destination_path );

                    // Where the source content is
                    $content_directory = \CROSSROAD_BASE_DIR . '/content/' . $contentType;
                    
                    foreach( $entries as $entry ) {
                        $this->renderer->renderSinglePage( $entry, [ $entry->contentType . '-single', $entry->contentType, 'index' ] );
                        $this->totalPages++;
                    }
                }

                // process all content
                usort( $entries, 'CR\cr_sort' );

                if ( isset( $contentConfig[ 'index' ] ) && $contentConfig[ 'index' ] ) {
                    $this->renderer->renderIndexPage( $entries, $contentType, '/' . $contentType, [ 'index' ] );
                }

                if ( $contentType == $this->config[ 'site' ][ 'home' ] ) {
                    $this->renderer->renderIndexPage( $entries, $contentType, '', [ 'index' ] );
                }

                 // tax
                $taxTerms = $this->entries->getTaxTerms( $contentType );
                if ( count( $taxTerms ) ) {
                    Utils::mkdir( CROSSROAD_PUBLIC_DIR . '/' . $contentType . '/taxonomy' );
                    foreach( $taxTerms as $term ) {
                        Utils::mkdir( CROSSROAD_PUBLIC_DIR . '/' . $contentType . '/taxonomy/' . $term );

                        $entries = $this->entries->getTax( $contentType, $term );

                        usort( $entries, 'CR\cr_sort' );

                        if ( count( $entries ) ) {
                            $this->renderer->renderIndexPage( $entries, $contentType, '/' . $contentType . '/taxonomy/' . $term, [ 'index' ] );
                        }
                    }
                }
            }
        }

        $this->_writeRobots();
        $this->_writeSitemapXml();

        $total_time = microtime( true ) - $this->startTime;
        echo "..total page(s) generated, " . $this->totalPages . " - build completed in " . sprintf( "%0.4f", $total_time ) . "s\n";
    }

    private function _writeSitemapXml() {
        $sitemapXml  = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $sitemapXml .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

        if ( isset( $this->config[ 'content' ][ 'types' ] ) ) {
            foreach( $this->config[ 'content' ][ 'types' ] as $contentType => $contentConfig ) {
                $entries = $this->entries->get( $contentType );

                usort( $entries, 'CR\cr_sort' );

                foreach( $entries as $entry ) {
                    $sitemapXml = $this->_addSitemapEntry( $sitemapXml, $entry->url );
                }

                $taxTerms = $this->entries->getTaxTerms( $contentType );
                if ( count( $taxTerms ) ) {
                    $taxUrl = $this->config[ 'site' ][ 'url' ] . '/' . $contentType . '/taxonomy';
                    foreach( $taxTerms as $term ) {
                        $sitemapXml = $this->_addSitemapEntry( $sitemapXml, $taxUrl . '/' . $term, 'monthly' );
                    }
                }
            }
        }

        $sitemapXml = $this->_addSitemapEntry( $sitemapXml, $this->config[ 'site' ][ 'url' ], 'daily' );

        $sitemapXml .= "</urlset>\n";

        file_put_contents( CROSSROAD_PUBLIC_DIR . '/sitemap.xml', $sitemapXml );
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
        $robots = "user-agent: *\ndisallow: /assets/css/\ndisallow: /assets/js/\nallow: /\n\nUser-agent: Twitterbot\nallow: /\nSitemap: " . $this->config[ 'site' ][ 'url' ] . "/sitemap.xml";
        file_put_contents( CROSSROAD_PUBLIC_DIR . '/robots.txt', $robots );
        echo "....writing robots.txt\n";
    }

    private function _setupMenus() {
        $this->menu = new Menu();
        $this->menu->loadMenus();
    }

    private function _setupTheme() {
        $this->theme = new Theme( $this->config[ 'site' ][ 'theme' ], CROSSROAD_THEME_DIR );
        echo "..attemping to load theme [" . $this->theme->name() . "]\n";

        if ( !$this->theme->isSane() ) {
            throw new ThemeException( 'Broken theme' );
        }

        $this->theme->loadConfig();
        echo "....theme successfully loaded\n";

        $this->theme->processAssets( CROSSROAD_PUBLIC_DIR . '/assets' );
    }
}