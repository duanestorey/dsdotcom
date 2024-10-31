<?php

namespace CR;

class Builder {
    var $config = null;
    var $start_time = null;
    var $total_pages = 0;
    var $template_engine = null;
    var $theme = null;

    public function __construct( $config ) {
        $this->config = $config;

        $this->template_engine = new TemplateEngine();
        $this->template_engine->set_template_dir( CROSSROAD_THEME_DIR . '/' . $config[ 'site' ][ 'theme' ] );
    }

    public function run() {
        @mkdir( CROSSROAD_PUBLIC_DIR );
        @mkdir( CROSSROAD_PUBLIC_DIR . '/assets' );

        $this->_setup_theme();

        $this->start_time = microtime( true );

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

                            $output_file = CROSSROAD_PUBLIC_DIR . '/' . $content_type . '/' . pathinfo( $markdown_file, PATHINFO_FILENAME ) . '.html';

                            $params = $this->_get_default_render_params( 
                                array( $content_type, $content_type . '-' . pathinfo( $markdown_file, PATHINFO_FILENAME ) ) 
                            );

                            $params->content = new Content;
                            $params->content->markdown_html = $markdown->html();
                            $params->content->markdown_file = $markdown_file;
                            $params->content->url = '/' . $content_type . '/' . pathinfo( $markdown_file, PATHINFO_FILENAME ) . '.html';

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

                // process all content
                usort( $all_content, 'CR\cr_sort' );

                $params = $this->_get_default_render_params( 
                    array( $content_type . '-' . pathinfo( $markdown_file, PATHINFO_FILENAME ) ) 
                );

                $content_per_page = 10;
                if ( isset( $this->config[ 'options' ][ 'content_per_page' ] ) ) {
                    $content_per_page = $this->config[ 'options' ][ 'content_per_page' ];
                }

                $params->page->title = 'index';

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

                $params->pagination->links = $this->_get_pagination_links( $content_type, $params->pagination->total_pages );

                $template_name = $this->template_engine->locate_template( [ 'index' ] );
                if ( $template_name ) {
                    while ( $params->pagination->current_page <= $params->pagination->total_pages ) {
                        if ( $params->pagination->current_page == 1 ) {
                            $filename = CROSSROAD_PUBLIC_DIR . '/' . $content_type . '/index.html';
                            $params->pagination->cur_page_link = '/' . $content_type . '/index.html';
                        } else {
                            $filename = CROSSROAD_PUBLIC_DIR . '/' . $content_type . '/index-page-' . $params->pagination->current_page . '.html';
                            $params->pagination->cur_page_link = '/' . $content_type . '/index-page-' . $params->pagination->current_page . '.html';
                        }

                        if ( $params->pagination->current_page != $params->pagination->total_pages ) {
                            $params->pagination->next_page_link = '/' . $content_type . '/index-page-' . ( $params->pagination->current_page + 1 ). '.html';
                        } else {
                            $params->pagination->next_page_link = '';
                        }

                        $params->content = array_slice( $all_content, ( $params->pagination->current_page - 1 ) * $content_per_page, $content_per_page );
  
                        $rendered_html = $this->template_engine->render( $template_name, $params );
                        file_put_contents( $filename, $rendered_html );  

                        $params->pagination->current_page++;

                        $params->pagination->prev_page_link = $params->pagination->cur_page_link;
                    }
                }
            }
        }
        $total_time = microtime( true ) - $this->start_time;
        echo "..total page(s) generated, " . $this->total_pages . " - build completed in " . sprintf( "%0.4f", $total_time ) . "s\n";
    }

    private function _find_and_fix_image( $content_type, $image_file, $current_path, $destination_path, $publish_date, $search_dirs = [ '', 'images/' ] ) {
        $new_location = $image_file;

        foreach( $search_dirs as $search_dir ) {
            $modified_image_file = $search_dir . $image_file;

            $image_filename_only = pathinfo( $modified_image_file, PATHINFO_BASENAME );

            $image_destination_path_with_date = $destination_path . '/' . date( 'Y', $publish_date );
            @mkdir( $image_destination_path_with_date );

            if ( file_exists( $current_path . '/' . $modified_image_file ) ) {
                $destination_file = $image_destination_path_with_date . '/' . $image_filename_only;

                if ( !file_exists( $destination_file ) ) {
                    echo "........copying image [" . $image_filename_only . "] to [" . $destination_file . "]\n";
                    Utils::copy_file( $current_path . '/' . $modified_image_file, $destination_file );                
                }

                $new_location = '../assets/' . $content_type . '/' . date( 'Y', $publish_date ) . '/' . $image_filename_only;
                break;
            }
        }
  
        return $new_location;
    }

    private function _get_pagination_links( $content_type, $total_pages ) {
        $links = array();

        for ( $i = 0; $i < $total_pages; $i++ ) {
            $page = new \stdClass;

            $page->num = $i + 1;
            $page->url = $i == 0 ? '/' . $content_type . '/index.html' : '/' . $content_type . '/index-page-' . ( $i+1 ) . '.html';

            $links[] = $page;
        }

        return $links;
    }

    private function _get_default_render_params( $body_classes_raw ) {
        $params = new \stdClass;
        $params->site = new \stdClass;
        $params->site->title = $this->config[ 'site' ][ 'name' ];

        $params->page = new \stdClass;
        $params->page->asset_url = '../assets';
        $params->page->asset_hash = $this->theme->get_asset_hash();
        $params->page->body_classes_raw = $body_classes_raw;
        $params->page->body_classses = implode( ' ', $params->page->body_classes_raw );    

        return $params;
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

    private function _find_markdown_files( $directory ) {
        $all_files = array();

        $filenames = array_diff( scandir( $directory ), array( '.', '..' ) );
        foreach( $filenames as $one_file ) {
            $full_path = $directory . '/' . $one_file;
            if ( is_dir( $full_path ) ) {
                $all_files = array_merge( $all_files, $this->_find_markdown_files( $full_path ) );
            } else if ( pathinfo( $full_path, PATHINFO_EXTENSION ) == 'md' ) {
                $all_files[] = $full_path;
            }
        }

        return $all_files;
    }
}