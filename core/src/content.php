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
    var $rel_url = '';
    var $markdown_file = '';
    var $markdown_html = '';
    var $featured_image = false;
    var $featured_image_width = 0;
    var $featured_image_height = 0;
    var $description = '';
    var $slug = '';
    var $unique = '';
    var $taxonomy = '';
    var $taxonomy_links = [];
    var $content_type = '';
    var $class_name = '';

    public function __construct() {
        $this->publishDate = time();
    }

    public function setTitle( $title ) {
        $this->title = $title;
    }

    public function excerpt( $length = 600, $include_ellipsis = true ) {
        $str = '';
        $words = explode( ' ', strip_tags( $this->markdown_html ) );

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