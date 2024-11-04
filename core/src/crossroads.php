<?php
/*
    All code copyright (c) 2024 by Duane Storey - All rights reserved
    You may use, distribute and modify this code under the terms of GPL version 3.0 license or later
*/

namespace CR;

define( 'CROSSROADS_VERSION', '1.00' );

require_once( 'builder.php' );
require_once( 'entries.php' );
require_once( 'markdown.php' );
require_once( 'template-engine.php' );
require_once( 'exception.php' );
require_once( 'content.php' );
require_once( 'menu.php' );
require_once( 'yaml.php' );
require_once( 'theme.php' );
require_once( 'utils.php' );
require_once( 'plugin.php' );
require_once( 'plugin-manager.php' );
require_once( 'renderer.php' );

require_once( CROSSROAD_CORE_DIR . '/plugins/image-plugin.php' );
require_once( CROSSROAD_CORE_DIR . '/plugins/seo-plugin.php' );
require_once( CROSSROAD_CORE_DIR . '/plugins/wordpress-plugin.php' );

class Engine {
    var $builder = null;
    var $config = null;

    public function __construct() {}

    public function run( $argc, $argv ) {
        $this->_branding();

        $this->_loadConfig();

        if ( $argc == 1 ) {
            $this->_usage();
        } else {
            switch( $argv[ 1 ] ) {
                case 'build':
                    $this->_build();
                    break;
                case 'import':
                    $this->_import();
                    break;
                case 'serve':
                    $this->_serve();
                    break;
            }
        }
    }

    private function _loadConfig() {
        $this->config = YAML::parse_file( CROSSROAD_BASE_DIR . '/_config/site.yaml' );
    }

    private function _checkConfig() {
        // check to make sure everything we need is here
    }

    private function _branding() {
        echo "Crossroads " . CROSSROADS_VERSION . " starting up\n";
    }

    private function _usage() {
        echo "..proper usage:\n\n";
        echo "php crossroads build                      Builds entire website\n";
        echo "php crossroads serve                      Start webserver\n";
        echo "php crossroads import wordpress <url>     Import from a WordPress website\n";
    }

    private function _import() {
        require_once( 'core/src/importers/wordpress.php' );
        $importer = new Importers\WordPress;
        $importer->import( 'https://old.duanestorey.com' );
    }

    private function _build() {
        echo "..building website\n";

        $this->builder = new Builder( $this->config );

        try {
            $this->builder->run();
        } catch( Exception $e ) {
            echo "..build stopped due to exception [" . $e->name() . "] with message [" . $e->msg() . "]\n";
        }
        
    }

    private function _serve() {
        echo "..building website\n";
    }
}