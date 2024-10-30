<?php

namespace CR;

define( 'CROSSROADS_VERSION', '1.00' );

require_once( 'src/builder.php' );

class Engine {
    var $builder = null;

    public function __construct() {}

    public function run( $argc, $argv ) {
        $this->_branding();

        if ( $argc == 1 ) {
            $this->_usage();
        } else {
            switch( $argv[ 1 ] ) {
                case 'build':
                    $this->_build();
                    break;
                case 'serve':
                    $this->_serve();
                    break;
            }
        }
    }

    private function _branding() {
        echo "Crossroads " . CROSSROADS_VERSION . " starting up\n";
    }

    private function _usage() {
        echo "..proper usage:\n\n";
        echo "php crossroads.php build      Builds entire website\n";
        echo "php crossroads.php serve      Start webserver\n";
    }

    private function _build() {
        echo "..building website\n";

        $this->builder = new Builder;
        $this->builder->run();
    }

    private function _serve() {
        echo "..building website\n";
    }
}