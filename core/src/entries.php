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

            LOG( "Loading content type [" . $contentType . "]", 1, LOG::INFO );

            $content_directory = \CROSSROADS_CONTENT_DIR . '/' . $contentType;

            $allMarkdownFiles = $this->_findMarkdownFiles( $content_directory );
            if ( is_array( $allMarkdownFiles ) && count( $allMarkdownFiles ) ) {
                foreach( $allMarkdownFiles as $markdownFile ) {
                    LOG( "Processing content file " . pathinfo( $markdownFile, PATHINFO_FILENAME ), 2, LOG::DEBUG );

                    $markdown = new Markdown();
                    if ( $markdown->loadFile( $markdownFile ) ) {    
                        $content = new Content;
                        $content->slug = $this->getSlugFromName( pathinfo( $markdownFile, PATHINFO_FILENAME ) );
                        $content->taxonomy = array();

                        if ( $front = $markdown->frontMatter() ) {
                            $content->title = $this->_findDataInFrontMatter( [ 'title' ], $front,$content->title );
                            $content->slug = $this->_findDataInFrontMatter( [ 'slug' ], $front,$content->slug );
                            $content->publishDate = strtotime( $this->_findDataInFrontMatter( [ 'date', 'publishDate' ], $front, date( 'Y-m-d' ) ) );
                            $content->featuredImage = $this->_findDataInFrontMatter( [ 'featuredImage', 'coverImage', 'heroImage' ], $front, $content->featuredImage );
                            $content->description = $this->_findDataInFrontMatter( [ 'description' ], $front, $content->description );
                            $content->taxonomy = array_merge( $this->_findDataInFrontMatter( [ 'category', 'categories' ], $front, [] ), $content->taxonomy ); 
                            $content->taxonomy = array_merge( $this->_findDataInFrontMatter( [ 'tag', 'tags' ], $front, [] ), $content->taxonomy );
                        }

                        $contentLink = '/' . $contentType . '/' . $content->slug . '.html';

                        $content->contentType = $contentType;
                        $content->markdownHtml = $markdown->html();
                        $content->markdownFile = $markdownFile;
                        $content->url = Utils::fixPath( $this->config->get( 'site.url' ) ) . $contentLink;
                        $content->relUrl = $contentLink;
                        $content->unique = md5( $contentLink ); 
                        $content->className = $content->slug;

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