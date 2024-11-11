<?php

namespace CR;

class Entries {
    var $config = null;
    var $entries = array();
    var $tax = array();
    var $totalEntries = 0;

    public function __construct( $config ) {
        $this->config = $config;
    }

    public function getEntryCount() {
        return $this->totalEntries;
    }

    public function get( $contentType ) {
        if ( isset( $this->entries[ $contentType ] ) ) {
            return $this->entries[ $contentType ];
        } 

        return false;
    }

    function getTaxTypes( $contentType ) {
        if ( isset( $this->tax[ $contentType ] ) ) {
            $values = array_keys( $this->tax[ $contentType ] );
            sort( $values );
            return $values;
        }

        return false;
    }

    function getTaxTerms( $contentType, $taxType ) {
        if ( isset( $this->tax[ $contentType ][ $taxType ] ) ) {
            $values = array_keys( $this->tax[ $contentType ][ $taxType ]);
            sort( $values );
            return $values;
        }

        return false;
    }

    function getTax( $contentType, $taxType, $term ) {
        if ( isset( $this->tax[ $contentType ] ) && isset( $this->tax[ $contentType ][ $taxType ] ) && isset( $this->tax[ $contentType ][ $taxType ][ $term ] ) )  {
            return $this->tax[ $contentType ][ $taxType ][ $term ];
        }

        return false;
    }

    public function getAll() {
        $allEntries = array();

        foreach( $this->config->get( 'content', [] ) as $contentType => $contentConfig ) {
            if ( isset( $this->entries[ $contentType ] ) ) {
                $allEntries = array_merge( $allEntries, $this->entries[ $contentType ] );
            }
        }

        return $allEntries;
    }

    public function loadAll() {
        foreach( $this->config->get( 'content', [] ) as $contentType => $contentConfig ) {
            if ( !isset( $this->entries[ $contentType ] ) ) {
                $this->entries[ $contentType ] = [];
                $this->tax[ $contentType ] = [];
            }

            LOG( sprintf( _i18n( 'core.class.entries.processing.loading' ), $contentType ), 1, LOG::INFO );

            $content_directory = \CROSSROADS_CONTENT_DIR . '/' . $contentType;

            $allMarkdownFiles = $this->_findMarkdownFiles( $content_directory );
            if ( is_array( $allMarkdownFiles ) && count( $allMarkdownFiles ) ) {
                foreach( $allMarkdownFiles as $markdownFile ) {
                    LOG( sprintf( _i18n( 'core.class.entries.processing.content' ), pathinfo( $markdownFile, PATHINFO_FILENAME ) ), 2, LOG::DEBUG );

                    $markdown = new Markdown();
                    if ( $markdown->loadFile( $markdownFile ) ) {    
                        $content = new Content( $this->config, $contentType, $contentConfig );
                        $content->slug = $this->getSlugFromName( pathinfo( $markdownFile, PATHINFO_FILENAME ) );
                        $content->markdownFile = $markdownFile;
                        $content->html = $markdown->html();
                        $content->modifiedDate = filemtime( $markdownFile );

                        if ( $front = $markdown->frontMatter() ) {
                            $content->title = $this->_findDataInFrontMatter( [ 'title' ], $front,$content->title );
                            $content->slug = $this->_findDataInFrontMatter( [ 'slug' ], $front,$content->slug );
                            $content->publishDate = strtotime( $this->_findDataInFrontMatter( [ 'date', 'publishDate' ], $front, date( 'Y-m-d' ) ) );
                            $content->featuredImage = $this->_findDataInFrontMatter( [ 'featuredImage', 'coverImage', 'heroImage' ], $front, $content->featuredImage );
                            $content->description = $this->_findDataInFrontMatter( [ 'description' ], $front, $content->description );

                            if ( isset( $contentConfig[ 'taxonomy' ] ) ) {
                                foreach( $contentConfig[ 'taxonomy' ] as $tax => $variations ) {
                                    $content->taxonomy[ $tax ] = $this->_findDataInFrontMatter( $variations, $front, [] ); 
                                    $content->taxonomy[ $tax ] = array_map( function( $e ) { return Utils::cleanTerm( $e ); }, $content->taxonomy[ $tax ] );
                                }
                            }
                        }

                        $content->calculate();
                        $content->processImages();
                                 
                        if ( count( $content->taxonomy ) ) {
                            foreach( $content->taxonomy as $tax => $terms ) {
                                foreach( $terms as $term ) {
                                    $this->tax[ $contentType ][ $tax ][ $term ][] = $content;
                                    $content->taxonomyLinks[ $tax ][ $term ] = '/' . $contentType . '/' . $tax . '/' . $term;
                                }
                            }
                        }

                        $this->entries[ $contentType ][] = $content;
                        $this->totalEntries++;
                    }
                }
            }
        }
    }

    private function getSlugFromName( $name ) {
        if ( preg_match( '#(\d+-\d+-\d+)-(.*)#', $name, $match ) ) {
            $name = $match[ 2 ];
        }
        return $name;
    }

    private function _findDataInFrontMatter( $fields, $frontMatter, $default ) {
        foreach( $fields as $field ) {
            if ( isset( $frontMatter[ $field ] ) ) {
                return $frontMatter[ $field ];
            }
        }

        return $default;
    }

    private function _findMarkdownFiles( $directory ) {
        return Utils::findAllFilesWithExtension( $directory, 'md' );
    }    
}