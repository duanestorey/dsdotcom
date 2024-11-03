<?php

namespace CR;

class Plugin {
    public function contentFilter( $content ) { 
        return $content;
    }

    public function templateParamFilter( $params ) {
        return $params;
    }
}