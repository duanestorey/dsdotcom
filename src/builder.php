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
                            }

                            $params->page->title = $params->content->title;

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
               
                if ( count( $all_content ) % $content_per_page == 0 ) {
                    $params->pagination->total_pages = intdiv( count( $all_content ), $content_per_page );
                } else {    
                    $params->pagination->total_pages = intdiv( count( $all_content ), $content_per_page ) + 1;
                }

                $params->content = array_slice( $all_content, 0, $content_per_page );

                $template_name = $this->template_engine->locate_template( [ 'index' ] );
                if ( $template_name ) {
                    $rendered_html = $this->template_engine->render( $template_name, $params );
                    file_put_contents( CROSSROAD_PUBLIC_DIR . '/' . $content_type . '/index.html', $rendered_html );
                }
            }
        }
        $total_time = microtime( true ) - $this->start_time;
        echo "..total page(s) generated, " . $this->total_pages . " - build completed in " . sprintf( "%0.4f", $total_time ) . "s\n";
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