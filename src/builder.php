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

        $this->renderer = new Renderer( $this->config, $this->template_engine );
    }

    public function run() {
        @mkdir( CROSSROAD_PUBLIC_DIR );
        @mkdir( CROSSROAD_PUBLIC_DIR . '/assets' );

        $this->_setup_theme();
        $this->_setup_menus();

        $this->start_time = microtime( true );

        // load all content here
        $this->entries = new Entries( $this->config, );
        $this->entries->load_all();

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
                        $params = $this->_get_default_render_params( 
                            array( $content_type, $content_type . '-' . pathinfo( $entry->markdown_file, PATHINFO_FILENAME ) ),
                            $entry->slug
                        ); 

                        $entry = $this->plugin_manager->content_filter( $entry );
                        $params->is_single = true;  
                        $params->content = $entry;

                        $params = $this->plugin_manager->template_param_filter( $params );

                        $this->renderer->render_single_page( $params, [ $params->content->content_type . '-single', $params->content->content_type, 'index' ] );

                        $this->total_pages++;
                    }
                }
            }

            $all_content = $this->entries->get( 'posts' );

            // process all content
            usort( $all_content, 'CR\cr_sort' );

            $this->_write_index_file( $all_content, 'posts', [ 'blog' ], '/' . 'posts', [ 'index' ] );

            if ( $content_type == 'posts' ) {
                $this->_write_index_file( $all_content, 'posts', [ 'home' ], '', [ 'index' ] );
            }
        }
        /*

        die;
        if ( isset( $this->config[ 'content' ][ 'types' ] ) ) {
            foreach( $this->config[ 'content' ][ 'types' ] as $content_type => $content_config ) {
                $all_content = array();

                echo "....processing content type [" . $content_type . "]\n";

                @mkdir( CROSSROAD_PUBLIC_DIR . '/' . $content_type );

                $image_destination_path = CROSSROAD_PUBLIC_DIR . '/assets/' . $content_type;
                @mkdir( $image_destination_path );
               
                $content_directory = \CROSSROAD_BASE_DIR . '/content/' . $content_type;

                $all_markdown_files = $this->_find_markdown_files( $content_directory );
                if ( is_array( $all_markdown_files ) && count( $all_markdown_files ) ) {
                    foreach( $all_markdown_files as $markdown_file ) {
                        echo "......building content " . pathinfo( $markdown_file, PATHINFO_FILENAME ) . "\n";

                        $markdown = new Markdown();
                        if ( $markdown->load_file( $markdown_file ) ) {
                            $this->total_pages++;

                            $content_slug = '/' . $content_type . '/' . pathinfo( $markdown_file, PATHINFO_FILENAME ) . '.html';

                            $output_file = CROSSROAD_PUBLIC_DIR . $content_slug;

                            $params = $this->_get_default_render_params( 
                                array( $content_type, $content_type . '-' . pathinfo( $markdown_file, PATHINFO_FILENAME ) ),
                                $content_slug
                            );

                            $params->content = new Content;
                            $params->content->markdown_html = $markdown->html();
                            $params->content->markdown_file = $markdown_file;
                            $params->content->url = Utils::fix_path( $this->config[ 'site' ][ 'url' ] ) . $content_slug;
                            $params->content->rel_url = $content_slug;
                            $params->content->slug = $content_slug;
                            $params->content->unique = md5( $content_slug );
                            $params->content->taxonomy = array();

                            $params->is_single = true;

                            $all_content[] = $params->content;

                            if ( $front = $markdown->front_matter() ) {
                                if ( isset( $front[ 'title' ] ) ) {
                                    $params->content->title = $front[ 'title' ];
                                }

                                if ( isset( $front[ 'date' ] ) ) {
                                    $params->content->publish_date = strtotime( $front[ 'date' ] );
                                }

                                if ( isset( $front[ 'coverImage' ] ) ) {
                                    $params->content->featured_image = $front[ 'coverImage' ];
                                }

                                if ( isset( $front[ 'description' ] ) ) {
                                    $params->content->description = $front[ 'description' ];
                                }

                                if ( isset( $front[ 'categories'] ) ) {
                                    $params->content->taxonomy = array_merge( $params->content->taxonomy, $front[ 'categories'] );
                                }

                                if ( isset( $front[ 'tags'] ) ) {
                                    $params->content->taxonomy = array_merge( $params->content->taxonomy, $front[ 'tags'] );
                                }
                            }

                            $params->content->taxonomy = array_map( function( $e ) { return str_replace( '-', ' ', $e ); }, $params->content->taxonomy );

                            if ( !$params->content->description ) {
                                $params->content->description = $params->content->excerpt( 120, false );
                            }

                            $params->page->title = $params->content->title;

                            // let's do image processing here, it's going to be ugly
                            $regexp = '<img[^>]+src=(?:\"|\')\K(.[^">]+?)(?=\"|\')';
                            if( preg_match_all( "/$regexp/", $params->content->markdown_html, $matches, PREG_SET_ORDER ) ) {
                                foreach( $matches as $images ) {
                                    $image_file = $images[ 0 ];

                                    $dest_url = $this->_find_and_fix_image( 
                                        $content_type,
                                        $image_file,
                                        pathinfo( $markdown_file, PATHINFO_DIRNAME ),
                                        $image_destination_path,
                                        $params->content->publish_date
                                    );

                                    $params->content->markdown_html = str_replace( $image_file, $dest_url, $params->content->markdown_html );

                                       //print_r( $images );
                                    if ( strpos( $images[0], "_7406" ) !== false ) {
                                     //   print_r( $params->content->markdown_html );
                                     //    print_r( $images ); die;
                                    }
                                   
                                }
                            }

                            if ( $params->content->featured_image ) {
                                $params->content->featured_image = $this->_find_and_fix_image( 
                                    $content_type,
                                    $params->content->featured_image,
                                    pathinfo( $markdown_file, PATHINFO_DIRNAME ),
                                    $image_destination_path,
                                    $params->content->publish_date
                                );
                            }

                            // Remove stupid captions
                            if ( preg_match_all( '#(\[caption\b[^\]]*\](.*)\[\/caption])#iU', $params->content->markdown_html, $matches, PREG_SET_ORDER ) ) {
                                foreach( $matches as $key => $match ) {
                                  //  print_r( $match ); die;

                                  // rewrite this, likely errors
                                    $replace = str_replace( '</a>', '</a><p class="caption text-center fst-italic">', $match[ 2 ] . '</p>' );
                                    $replace = str_replace( '/>', '/><p class="caption text-center fst-italic">', $match[ 2 ] . '</p>' );
                                    $params->content->markdown_html = str_replace( $match[ 0 ], $replace, $params->content->markdown_html );
                                }
                            }    

                            $template_name = $this->template_engine->locate_template( [ $content_type . '-single', $content_type, 'index' ] );
                            if ( $template_name ) {
                                $rendered_html = $this->template_engine->render( $template_name, $params );
                                file_put_contents( $output_file, $rendered_html );
                            }
                        }
                    }
                }
            

            }
        }

            */



        // write robots
        $robots = "user-agent: *\ndisallow: /assets/\n";
        file_put_contents( CROSSROAD_PUBLIC_DIR . '/robots.txt', $robots );
        echo "....writing robots.txt\n";

        $total_time = microtime( true ) - $this->start_time;
        echo "..total page(s) generated, " . $this->total_pages . " - build completed in " . sprintf( "%0.4f", $total_time ) . "s\n";
    }

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

    private function _get_default_render_params( $body_classes_raw, $current_slug ) {
        $params = new \stdClass;
        $params->site = new \stdClass;
        $params->site->title = $this->config[ 'site' ][ 'name' ];

        $params->site->lang = 'en';
        if ( isset( $this->config[ 'site' ][ 'lang' ] ) ) {
            $params->site->lang = $this->config[ 'site' ][ 'lang' ];
        }

        $params->site->charset = 'utf-8';
        if ( isset( $this->config[ 'site' ][ 'charset' ] ) ) {
            $params->site->charset = $this->config[ 'site' ][ 'charset' ];
        }

        $params->menu = $this->menu->build( 'main', $current_slug );

        $params->page = new \stdClass;
        $params->page->asset_url = '../assets';
        $params->page->asset_hash = $this->theme->get_asset_hash();
        $params->page->body_classes_raw = $body_classes_raw;
        $params->page->body_classses = implode( ' ', $params->page->body_classes_raw );    

        $params->is_single = false;
        $params->is_home = false;

        return $params;
    }

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