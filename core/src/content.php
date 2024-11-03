<?php

namespace CR;

function cr_sort( $a, $b ) {
    if ( $a->publishDate == $b->publishDate ) {
        return 0;
    }

    return ( $b->publishDate < $a->publishDate ) ? -1 : 1;
}

class Content {
    var $title = '';
    var $publishDate = 0;
    var $url = '';
    var $relUrl = '';
    var $markdownFile = '';
    var $markdownHtml = '';
    var $featuredImage = false;
    var $description = '';
    var $slug = '';
    var $unique = '';
    var $taxonomy = '';
    var $taxonomyLinks = [];
    var $contentType = '';
    var $className = '';

    public function __construct() {
        $this->publishDate = time();
    }

    public function setTitle( $title ) {
        $this->title = $title;
    }

    public function excerpt( $length = 600, $include_ellipsis = true ) {
        $str = '';
        $words = explode( ' ', strip_tags( $this->markdownHtml ) );

        $len = 0;
        for ( $i = 0; $i < count( $words ); $i++ ) {
            $str = $str . $words[ $i ] . ' ';
            $len += strlen( $words[ $i ] ) + 1;

            if ( $len >= $length ) {
                break;
            }
        }
        /*
        $str = substr( strip_tags( $this->markdown_html ), 0, $length );
        */
        if ( $include_ellipsis ) {
            $str = $str . '...';
        }

        return rtrim( $str );
    }
}