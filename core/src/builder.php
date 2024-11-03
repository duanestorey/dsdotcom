<?php
/*
    All code copyright (c) 2024 by Duane Storey - All rights reserved
    You may use, distribute and modify this code under the terms of GPL version 3.0 license or later
*/

namespace CR;

class Builder {
    var $config = null;
    var $startTime = null;
    var $total_pages = 0;
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
            foreach( $this->config[ 'content' ][ 'types' ] as $contentType => $content_config ) {
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
                        $this->total_pages++;
                    }
                }

                // process all content
                usort( $entries, 'CR\cr_sort' );

                if ( isset( $content_config[ 'index' ] ) && $content_config[ 'index' ] ) {
                    $this->renderer->renderIndexPage( $entries, $contentType, '/' . $content_type, [ 'index' ] );
                }

                if ( $contentType == $this->config[ 'site' ][ 'home' ] ) {
                    $this->renderer->renderIndexPage( $entries, $contentType, '', [ 'index' ] );
                }

                 // tax
                $tax_terms = $this->entries->getTaxTerms( $contentType );
                if ( count( $tax_terms ) ) {
                    Utils::mkdir( CROSSROAD_PUBLIC_DIR . '/' . $contentType . '/taxonomy' );
                    foreach( $tax_terms as $term ) {
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
        echo "..total page(s) generated, " . $this->total_pages . " - build completed in " . sprintf( "%0.4f", $total_time ) . "s\n";
    }

    private function _writeSitemapXml() {
        $sitemap_xml  = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $sitemap_xml .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

        if ( isset( $this->config[ 'content' ][ 'types' ] ) ) {
            foreach( $this->config[ 'content' ][ 'types' ] as $content_type => $content_config ) {
                $entries = $this->entries->get( $content_type );

                usort( $entries, 'CR\cr_sort' );

                foreach( $entries as $entry ) {
                    $sitemap_xml = $this->_addSitemapEntry( $sitemap_xml, $entry->url );
                }

                $tax_terms = $this->entries->getTaxTerms( $content_type );
                if ( count( $tax_terms ) ) {
                    $tax_url = $this->config[ 'site' ][ 'url' ] . '/' . $content_type . '/taxonomy';
                    foreach( $tax_terms as $term ) {
                        $sitemap_xml = $this->_addSitemapEntry( $sitemap_xml, $tax_url . '/' . $term, 'monthly' );
                    }
                }
            }
        }

        $sitemap_xml = $this->_addSitemapEntry( $sitemap_xml, $this->config[ 'site' ][ 'url' ], 'daily' );

        $sitemap_xml .= "</urlset>\n";

        file_put_contents( CROSSROAD_PUBLIC_DIR . '/sitemap.xml', $sitemap_xml );
    }

    private function _addSitemapEntry( $sitemap_xml, $url, $freq = 'weekly' ) {
        $sitemap_xml .= "\t<url>\n";
        $sitemap_xml .= "\t\t<loc>" . $url . "</loc>\n";
        $sitemap_xml .= "\t\t<changefreq>" . $freq . "</changefreq>\n";
        $sitemap_xml .= "\t</url>\n";

        return $sitemap_xml;
    }

    private function _writeRobots() {
        // write robots
        $robots = "user-agent: *\ndisallow: /assets/css/\ndisallow: /assets/js/\nallow: /\n\nUser-agent: Twitterbot\nallow: /\nSitemap: " . $this->config[ 'site' ][ 'url' ] . "/sitemap.xml";
        file_put_contents( CROSSROAD_PUBLIC_DIR . '/robots.txt', $robots );
        echo "....writing robots.txt\n";
    }

    private function _setupMenus() {
        $this->menu = new Menu();
        $this->menu->load_menus();
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