<?php

namespace CR;

class Config {
    protected $config = null;

    public function __construct( $config ) {
        $this->config = $config;
    }

    public function get( $key, $default = false ) {
        if ( $this->config && isset( $this->config[ $key ] ) ) {
            return $this->config[ $key ];
        } else {
            return $default;
        }
    }
}