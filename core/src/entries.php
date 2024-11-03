<?php

namespace CR;

class Entries {
    var $config = null;
    var $entries = array();
    var $tax = array();

    public function __construct( $config ) {
        $this->config = $config;
    }

    public function get( $contentType ) {
        if ( isset( $this->entries[ $contentType ] ) ) {
            return $this->entries[ $contentType ];
        } 

        return false;
    }

    function getTaxTerms( $contentType ) {
        if ( isset( $this->tax[ $contentType ] ) ) {
            return array_keys( $this->tax[ $contentType ] );
        }

        return false;
    }

    function getTax( $contentType, $term ) {
        if ( isset( $this->tax[ $contentType ] ) && isset( $this->tax[ $contentType ][ $term ] ) )  {
            return $this->tax[ $contentType ][ $term ];
        }

        return false;
    }

    public function getAll() {
        $allEntries = array();

        if ( isset( $this->config[ 'content' ][ 'types' ] ) ) {
            foreach( $this->config[ 'content' ][ 'types' ] as $contentType => $contentConfig ) {
                if ( isset( $this->entries[ $contentType ] ) ) {
                    $allEntries = array_merge( $allEntries, $this->entries[ $contentType ] );
                }
            }
        }

        return $allEntries;
    }

    public function loadAll() {
        if ( isset( $this->config[ 'content' ][ 'types' ] ) ) {
            foreach( $this->config[ 'content' ][ 'types' ] as $contentType => $contentConfig ) {
                if ( !isset( $this->entries[ $contentType ] ) ) {
                    $this->entries[ $contentType ] = [];
                    $this->tax[ $contentType ] = [];
                }

                echo "....loading content type [" . $contentType . "]\n";

                $content_directory = \CROSSROAD_BASE_DIR . '/content/' . $contentType;

                $allMarkdownFiles = $this->_findMarkdownFiles( $content_directory );
                if ( is_array( $allMarkdownFiles ) && count( $allMarkdownFiles ) ) {
                    foreach( $allMarkdownFiles as $markdownFile ) {
                        echo "......processing content file " . pathinfo( $markdownFile, PATHINFO_FILENAME ) . "\n";

                        $markdown = new Markdown();
                        if ( $markdown->loadFile( $markdownFile ) ) {    
                            $contentSlug = '/' . $contentType . '/' . pathinfo( $markdownFile, PATHINFO_FILENAME ) . '.html';

                            $content = new Content;
                            $content->contentType = $contentType;
                            $content->markdownHtml = $markdown->html();
                            $content->markdownFile = $markdownFile;
                            $content->url = Utils::fixPath( $this->config[ 'site' ][ 'url' ] ) . $contentSlug;
                            $content->relUrl = $contentSlug;
                            $content->slug = $contentSlug;
                            $content->unique = md5( $contentSlug );
                            $content->taxonomy = array();
                            $content->className = pathinfo( $markdownFile, PATHINFO_FILENAME );

                            if ( $front = $markdown->frontMatter() ) {
                                if ( isset( $front[ 'title' ] ) ) {
                                    $content->title = $front[ 'title' ];
                                }

                                if ( isset( $front[ 'date' ] ) ) {
                                    $content->publishDate = strtotime( $front[ 'date' ] );
                                }

                                if ( isset( $front[ 'coverImage' ] ) ) {
                                    $content->featuredImage = $front[ 'coverImage' ];
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
                                    if ( !isset( $this->tax[ $contentType ][ $tax ] ) ) {
                                        $this->tax[ $contentType ][ $tax ] = [];
                                    }

                                    $this->tax[ $contentType ][ $tax ][] = $content;
                                    $content->taxonomyLinks[ $tax ] = '/' . $contentType . '/taxonomy/' . $tax;
                                }
                            }

                            $this->entries[ $contentType ][] = $content;
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