<?php

namespace CR;

function cr_sort( $a, $b ) {
    if ( $a->publish_date == $b->publish_date ) {
        return 0;
    }

    return ( $b->publish_date < $a->publish_date ) ? -1 : 1;
}

class Content {
    var $title = '';
    var $publish_date = 0;
    var $url = '';
    var $rel_url = '';
    var $markdown_file = '';
    var $markdown_html = '';
    var $featured_image = false;
    var $description = '';
    var $slug = '';
    var $unique = '';
    var $taxonomy = '';
    var $content_type = '';

    public function __construct() {
        $this->publish_date = time();
    }

    public function set_title( $title ) {
        $this->title = $title;
    }

    public function excerpt( $length = 500, $include_ellipsis = true ) {
        $str = substr( strip_tags( $this->markdown_html ), 0, $length );
        if ( $include_ellipsis ) {
            $str = $str . '...';
        }

        return $str;
    }
}