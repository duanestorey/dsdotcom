<?php

namespace CR;

class Entries {
    var $config = null;
    var $entries = array();
    var $tax = array();

    public function __construct( $config ) {
        $this->config = $config;
    }

    public function get( $content_type ) {
        if ( isset( $this->entries[ $content_type ] ) ) {
            return $this->entries[ $content_type ];
        } 

        return false;
    }

    function getTaxTerms( $content_type ) {
        if ( isset( $this->tax[ $content_type ] ) ) {
            return array_keys( $this->tax[ $content_type ] );
        }

        return false;
    }

    function getTax( $content_type, $term ) {
        if ( isset( $this->tax[ $content_type ] ) && isset( $this->tax[ $content_type ][ $term ] ) )  {
            return $this->tax[ $content_type ][ $term ];
        }

        return false;
    }

    public function getAll() {
        $all_entries = array();

        if ( isset( $this->config[ 'content' ][ 'types' ] ) ) {
            foreach( $this->config[ 'content' ][ 'types' ] as $content_type => $content_config ) {
                if ( isset( $this->entries[ $content_type ] ) ) {
                    $all_entries = array_merge( $all_entries, $this->entries[ $content_type ] );
                }
            }
        }

        return $all_entries;
    }

    public function loadAll() {
        if ( isset( $this->config[ 'content' ][ 'types' ] ) ) {
            foreach( $this->config[ 'content' ][ 'types' ] as $content_type => $content_config ) {
                if ( !isset( $this->entries[ $content_type ] ) ) {
                    $this->entries[ $content_type ] = [];
                    $this->tax[ $content_type ] = [];
                }

                echo "....loading content type [" . $content_type . "]\n";

                $content_directory = \CROSSROAD_BASE_DIR . '/content/' . $content_type;

                $all_markdown_files = $this->_findMarkdownFiles( $content_directory );
                if ( is_array( $all_markdown_files ) && count( $all_markdown_files ) ) {
                    foreach( $all_markdown_files as $markdown_file ) {
                        echo "......processing content file " . pathinfo( $markdown_file, PATHINFO_FILENAME ) . "\n";

                        $markdown = new Markdown();
                        if ( $markdown->load_file( $markdown_file ) ) {    
                            $content_slug = '/' . $content_type . '/' . pathinfo( $markdown_file, PATHINFO_FILENAME ) . '.html';

                            $content = new Content;
                            $content->content_type = $content_type;
                            $content->markdown_html = $markdown->html();
                            $content->markdown_file = $markdown_file;
                            $content->url = Utils::fixPath( $this->config[ 'site' ][ 'url' ] ) . $content_slug;
                            $content->rel_url = $content_slug;
                            $content->slug = $content_slug;
                            $content->unique = md5( $content_slug );
                            $content->taxonomy = array();
                            $content->class_name = pathinfo( $markdown_file, PATHINFO_FILENAME );

                            if ( $front = $markdown->front_matter() ) {
                                if ( isset( $front[ 'title' ] ) ) {
                                    $content->title = $front[ 'title' ];
                                }

                                if ( isset( $front[ 'date' ] ) ) {
                                    $content->publishDate = strtotime( $front[ 'date' ] );
                                }

                                if ( isset( $front[ 'coverImage' ] ) ) {
                                    $content->featured_image = $front[ 'coverImage' ];
                                }

                                if ( isset( $front[ 'description' ] ) ) {
                                    $content->description = $front[ 'description' ];
                                }

                                if ( isset( $front[ 'categories'] ) ) {
                                    $content->taxonomy = array_merge( $content->taxonomy, $front[ 'categories'] );
                                }

                                if ( isset( $front[ 'tags'] ) ) {
                                    $content->taxonomy = array_merge( $content->taxonomy, $front[ 'tags'] );
                                }
                            }

                            $content->taxonomy = array_map( function( $e ) { return Utils::cleanTerm( $e ); }, $content->taxonomy );
                            if ( count( $content->taxonomy ) ) {
                                foreach( $content->taxonomy as $tax ) {
                                    if ( !isset( $this->tax[ $content_type ][ $tax ] ) ) {
                                        $this->tax[ $content_type ][ $tax ] = [];
                                    }

                                    $this->tax[ $content_type ][ $tax ][] = $content;
                                    $content->taxonomy_links[ $tax ] = '/' . $content_type . '/taxonomy/' . $tax;
                                }
                            }

                            $this->entries[ $content_type ][] = $content;
                        }
                    }
                }
            }
        }
    }


    private function _findMarkdownFiles( $directory ) {
        return Utils::findAllFilesWithExtension( $directory, 'md' );
    }    
}