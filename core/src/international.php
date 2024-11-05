<?php

namespace CR;

class International {
    private static $instance = null;
    protected $strings = [];

    protected function __construct() {}

    static function instance() {
        if ( self::$instance == null ) {
            self::$instance = new International();
        }

        return self::$instance;
    }   

    public function loadLocaleFile( $file ) {
        $strings = YAML::parse_file( $file, true );

        if ( $strings ) {
            $this->strings = array_merge( $this->strings, $strings );

            ksort( $this->strings );
        }

       //print_r( $this->strings );
    }

    public function get( $name ) {
        if ( isset( $this->strings[ $name ] ) ) {
            return $this->strings[ $name ];
        }

        return '';
    }
}

function _i18n( $str ) {
    return International::instance()->get( $str );
}