<?php

namespace CR;

define( 'CROSSROADS_VERSION', '1.00' );

require_once( 'src/builder.php' );
require_once( 'src/markdown.php' );
require_once( 'src/template-engine.php' );
require_once( 'src/yaml.php' );
require_once( 'lib/parsedown/Parsedown.php' );

class Engine {
    var $builder = null;
    var $config = null;

    public function __construct() {}

    public function run( $argc, $argv ) {
        $this->_load_config();

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

    private function _load_config() {
        $this->config = YAML::parse_file( 'config/site.yaml' );
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

        $this->builder = new Builder( $this->config );
        $this->builder->run();
    }

    private function _serve() {
        echo "..building website\n";
    }
}