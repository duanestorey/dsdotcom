<?php

namespace CR;

class SeoPlugin extends Plugin {
    var $config = null;

    public function __construct( $config ) {
        parent::__construct( 'seo' );

        $this->config = $config;
    }

    public function processOne( $content ) {
        $content->title = sprintf( "%s - %s", $content->title, $this->config->get( 'site.name' ) );

        if ( !$content->description ) {
            $content->description = $content->excerpt( 120, false );
        }

        return $content;
    }    
}