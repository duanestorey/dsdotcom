<?php

namespace CR;

class WordPressPlugin extends Plugin {
    var $config = null;

    public function __construct( $config ) {
        $this->config = $config;
    }

    public function content_filter( $content ) {
        // Remove stupid captions
        if ( preg_match_all( '#(\[caption\b[^\]]*\](.*)\[\/caption])#iU', $content->markdown_html, $matches, PREG_SET_ORDER ) ) {
            foreach( $matches as $key => $match ) {
                // rewrite this, likely errors
                $replace = str_replace( '</a>', '</a><p class="caption text-center fst-italic">', $match[ 2 ] . '</p>' );
                $replace = str_replace( '/>', '/><p class="caption text-center fst-italic">', $match[ 2 ] . '</p>' );
                $content->markdown_html = str_replace( $match[ 0 ], $replace, $content->markdown_html );
            }
        }       
        return $content;
    }    

    public function template_param_filter( $params ) {
        return $params;
    }
}