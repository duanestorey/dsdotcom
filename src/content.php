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
    var $markdown_file = '';
    var $markdown_html = '';

    public function __construct() {
        $this->publish_date = time();
    }

    public function set_title( $title ) {
        $this->title = $title;
    }
}