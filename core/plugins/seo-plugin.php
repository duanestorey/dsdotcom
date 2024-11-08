<?php

namespace CR;

class SeoPlugin extends Plugin {
    var $config = null;

    public function __construct( $config ) {
        parent::__construct( 'seo' );

        $this->config = $config;
    }

    public function contentFilter( $content ) {
        return $content;
    }    

    public function templateParamFilter( $params ) {
        $params->page->title = sprintf( "%s - %s", $params->content->title, $this->config->get( 'site.name' ) );

        if ( !$params->content->description ) {
            $params->content->description = $params->content->excerpt( 120, false );
        }

        return $params;
    }

}