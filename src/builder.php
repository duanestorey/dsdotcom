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

                            $params = new \stdClass;
                            $params->site = new \stdClass;
                            $params->site->title = $this->config[ 'site' ][ 'name' ];

                            $params->markdown_html = $markdown->html();
                            
                            $params->publish_date = time();
                            $params->asset_url = '../assets';
                            $params->body_classes_raw = array( $content_type, 'slug-' . pathinfo( $markdown_file, PATHINFO_FILENAME ), 'blog' );
                            $params->body_classses = implode( ' ', $params->body_classes_raw );

                            if ( $front = $markdown->front_matter() ) {
                                if ( isset( $front[ 'title' ] ) ) {
                                    $params->title = $front[ 'title' ];
                                }

                                if ( isset( $front[ 'date' ] ) ) {
                                    $params->publish_date = strtotime( $front[ 'date' ] );
                                }
                            }

                            $template_name = $content_type . '-single.latte';
                            if ( $this->template_engine->template_exists( $template_name ) ) {
                                $rendered_html = $this->template_engine->render( $template_name, $params );
                                file_put_contents( $output_file, $rendered_html );
                            }
                        }
                    }
                }
            }
        }
        $total_time = microtime( true ) - $this->start_time;
        echo "..total page(s) generated, " . $this->total_pages . " - build completed in " . sprintf( "%0.4f", $total_time ) . "s\n";
    }

    private function _setup_theme() {
        $theme = new Theme( $this->config[ 'site' ][ 'theme' ], CROSSROAD_THEME_DIR );
        echo "..attemping to load theme [" . $theme->name() . "]\n";

        if ( !$theme->is_sane() ) {
            throw new ThemeException( 'Broken theme' );
        }

        $theme->load_config();
        echo "....theme successfully loaded\n";

        $theme->process_assets( CROSSROAD_PUBLIC_DIR . '/assets' );
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