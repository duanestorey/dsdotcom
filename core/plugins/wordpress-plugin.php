<?php
/*
    All code copyright (c) 2024 by Duane Storey - All rights reserved
    You may use, distribute and modify this code under the terms of GPL version 3.0 license or later
*/

namespace CR;

class WordPressPlugin extends Plugin {
    var $config = null;

    public function __construct( $config ) {
        parent::__construct( 'wordpress' );

        $this->config = $config;
    }

    public function processOne( $entry ) {
        $entry = $this->contentFilter( $entry );
        
        return $entry;
    }

    public function contentFilter( $content ) {
        // Remove stupid captions
        if ( preg_match_all( '#(\[caption\b[^\]]*\](.*)\[\/caption])#iU', $content->markdownHtml, $matches, PREG_SET_ORDER ) ) {
            foreach( $matches as $key => $match ) {
                // rewrite this, likely errors
                $replace = str_replace( '</a>', '</a><span class="caption text-center fst-italic">', $match[ 2 ] . '</span>' );
                $replace = str_replace( '/>', '/><span class="caption text-center fst-italic">', $match[ 2 ] . '</span>' );
                $content->markdownHtml = str_replace( $match[ 0 ], $replace, $content->markdownHtml );
            }
        }       

        // fix stray image closings
        if ( preg_match_all( '#<img(.*)/>#iU', $content->markdownHtml, $matches, PREG_SET_ORDER ) ) {
            foreach( $matches as $key => $match ) {
                // rewrite this, likely errors
                $fixed_image = str_replace( array( ' />', '/>' ), array( '>', '>' ), $match[ 0 ] );
                $content->markdownHtml = str_replace( $match[ 0 ], $fixed_image, $content->markdownHtml );
            }
        } 
        return $content;
    }    

    public function templateParamFilter( $params ) {
        return $params;
    }
}