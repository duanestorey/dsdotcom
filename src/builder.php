<?php

namespace CR;

class Builder {
    var $config = null;
    var $start_time = null;
    var $total_pages = 0;
    var $template_engine = null;
    var $entries = null;
    var $theme = null;
    var $menu = null;
    var $plugin_manager = null;
    var $renderer = null;

    public function __construct( $config ) {
        $this->config = $config;

        $this->template_engine = new TemplateEngine();
        $this->template_engine->set_template_dir( CROSSROAD_THEME_DIR . '/' . $config[ 'site' ][ 'theme' ] );

        $this->plugin_manager = new PluginManager( $this->config );
        $this->plugin_manager->install_plugin( new ImagePlugin( $this->config ) );
        $this->plugin_manager->install_plugin( new SeoPlugin( $this->config ) );
        $this->plugin_manager->install_plugin( new WordPressPlugin( $this->config ) );
    }

    public function run() {
        @mkdir( CROSSROAD_PUBLIC_DIR );
        @mkdir( CROSSROAD_PUBLIC_DIR . '/assets' );

        $this->_setup_theme();
        $this->_setup_menus();

        $this->renderer = new Renderer( $this->config, $this->template_engine, $this->plugin_manager, $this->menu, $this->theme );

        $this->start_time = microtime( true );

        // load all content here
        $this->entries = new Entries( $this->config, );
        $this->entries->load_all();

        // do all content filtering
        $all_entries = $this->entries->get_all();
        foreach( $all_entries as $entry ) {
             $entry = $this->plugin_manager->content_filter( $entry );
        }

        // build
        echo "..starting template building\n";
        if ( isset( $this->config[ 'content' ][ 'types' ] ) ) {
            foreach( $this->config[ 'content' ][ 'types' ] as $content_type => $content_config ) {
                $entries = $this->entries->get( $content_type );

                if ( $entries ) {
                    // Make the output directory for html
                    Utils::mkdir( CROSSROAD_PUBLIC_DIR . '/' . $content_type );

                    // Make the output directory for images
                    $image_destination_path = CROSSROAD_PUBLIC_DIR . '/assets/' . $content_type;
                    Utils::mkdir( $image_destination_path );

                    // Where the source content is
                    $content_directory = \CROSSROAD_BASE_DIR . '/content/' . $content_type;
                    
                    foreach( $entries as $entry ) {
                        $this->renderer->render_single_page( $entry, [ $entry->content_type . '-single', $entry->content_type, 'index' ] );
                        $this->total_pages++;
                    }
                }


                $all_content = $this->entries->get( $content_type );

                // process all content
                usort( $all_content, 'CR\cr_sort' );

                $this->renderer->render_index_page( $all_content, $content_type, [ 'blog' ], '/' . $content_type, [ 'index' ] );

                if ( $content_type == 'posts' ) {
                    $this->renderer->render_index_page( $all_content, $content_type, [], '', [ 'index' ] );
                }

            }
        }

        // write robots
        $robots = "user-agent: *\ndisallow: /assets/\n";
        file_put_contents( CROSSROAD_PUBLIC_DIR . '/robots.txt', $robots );
        echo "....writing robots.txt\n";

        $total_time = microtime( true ) - $this->start_time;
        echo "..total page(s) generated, " . $this->total_pages . " - build completed in " . sprintf( "%0.4f", $total_time ) . "s\n";
    }
    /*
    private function _write_index_file( $all_content, $content_type, $body_class_array, $path, $template_file_array ) {
        // this is wrong, but fix later
        $params = $this->_get_default_render_params( 
            $body_class_array,
            $path 
        );

        $content_per_page = 10;
        if ( isset( $this->config[ 'options' ][ 'content_per_page' ] ) ) {
            $content_per_page = $this->config[ 'options' ][ 'content_per_page' ];
        }

        $params->page->title = $this->config[ 'site' ][ 'description' ];

        $params->pagination = new \stdClass;
        $params->pagination->current_page = 1;
        $params->pagination->cur_page_link = '';
        $params->pagination->prev_page_link = '';
        $params->pagination->next_page_link = '';
        
        if ( count( $all_content ) % $content_per_page == 0 ) {
            $params->pagination->total_pages = intdiv( count( $all_content ), $content_per_page );
        } else {    
            $params->pagination->total_pages = intdiv( count( $all_content ), $content_per_page ) + 1;
        }

        $params->pagination->links = $this->_get_pagination_links( $path, $params->pagination->total_pages );

        $template_name = $this->template_engine->locate_template( $template_file_array );
        if ( $template_name ) {
            while ( $params->pagination->current_page <= $params->pagination->total_pages ) {
                if ( $params->pagination->current_page == 1 ) {
                    $filename = CROSSROAD_PUBLIC_DIR . $path . '/index.html';
                    $params->pagination->cur_page_link = $path . '/index.html';
                } else {
                    $filename = CROSSROAD_PUBLIC_DIR . $path . '/index-page-' . $params->pagination->current_page . '.html';
                    $params->pagination->cur_page_link = $path . '/index-page-' . $params->pagination->current_page . '.html';
                }

                if ( $params->pagination->current_page != $params->pagination->total_pages ) {
                    $params->pagination->next_page_link = $path . '/index-page-' . ( $params->pagination->current_page + 1 ). '.html';
                } else {
                    $params->pagination->next_page_link = '';
                }

                $params->content = array_slice( $all_content, ( $params->pagination->current_page - 1 ) * $content_per_page, $content_per_page );

                if ( $params->pagination->current_page == 1 && $path == '' ) {
                    $params->is_home = true;
                } else {
                    $params->is_home = false;
                }

                $rendered_html = $this->template_engine->render( $template_name, $params );
                file_put_contents( $filename, $rendered_html );  

                $params->pagination->current_page++;

                $params->pagination->prev_page_link = $params->pagination->cur_page_link;
            }
        }    
    }

    private function _get_pagination_links( $path, $total_pages ) {
        $links = array();

        for ( $i = 0; $i < $total_pages; $i++ ) {
            $page = new \stdClass;

            $page->num = $i + 1;
            $page->url = $i == 0 ? $path . '/index.html' : $path . '/index-page-' . ( $i+1 ) . '.html';

            $links[] = $page;
        }

        return $links;
    }
    */

    private function _setup_menus() {
        $this->menu = new Menu();
        $this->menu->load_menus();
    }

    private function _setup_theme() {
        $this->theme = new Theme( $this->config[ 'site' ][ 'theme' ], CROSSROAD_THEME_DIR );
        echo "..attemping to load theme [" . $this->theme->name() . "]\n";

        if ( !$this->theme->is_sane() ) {
            throw new ThemeException( 'Broken theme' );
        }

        $this->theme->load_config();
        echo "....theme successfully loaded\n";

        $this->theme->process_assets( CROSSROAD_PUBLIC_DIR . '/assets' );
    }
}