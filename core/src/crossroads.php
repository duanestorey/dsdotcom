<?php
/*
    All code copyright (c) 2024 by Duane Storey - All rights reserved
    You may use, distribute and modify this code under the terms of GPL version 3.0 license or later
*/

namespace CR;

define( 'CROSSROADS_VERSION', '0.0.3' );

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
require_once( 'log.php' );
require_once( 'config.php' );
require_once( 'web-server.php' );
require_once( 'log-listener.php' );
require_once( 'log-listener-shell.php' );

require_once( CROSSROAD_CORE_DIR . '/plugins/image-plugin.php' );
require_once( CROSSROAD_CORE_DIR . '/plugins/seo-plugin.php' );
require_once( CROSSROAD_CORE_DIR . '/plugins/wordpress-plugin.php' );

class Engine {
    var $builder = null;
    var $config = null;
    var $startTime = null;

    public function __construct() {
    }

    public function run( $argc, $argv ) {
        $this->_branding();
        $this->_loadConfig();

        if ( $argc <= 1 ) {
            $this->_usage();
        } else {
            $command = $argv[ 1 ];

            $allowable_commands = $this->_getAllowableCommands();
            foreach( $allowable_commands as $one_command => $required_params ) {
                if ( $command == $one_command ) {
                    // right command, let's check params
                    if ( $argc != ( $required_params + 2 ) ) {
                        $this->_usage();
                    } else {
                        // we are good to go
                        Log::instance()->installListener( new LogListenerShell() );

                        LOG( "Executing [" . strtoupper( $argv[ 1 ] ) . "] command", 0, LOG::INFO );
                        $this->startTime = microtime( true );

                        $function = '_' . $command;

                        $this->{$function}( $argc, $argv );

                        LOG( sprintf( "Finished executing [" . strtoupper( $argv[ 1 ] ) . "] command, total time taken %0.3fs", microtime( true ) - $this->startTime ), 0, LOG::INFO );
                    }
                }
            }
        }
    }

    private function _getAllowableCommands() {
        return array(
            'build' => 0,
            'import' => 2,
            'serve' => 0,
            'clean' => 0
        );
    }

    private function _loadConfig() {
        $this->config = new Config( YAML::parse_file( CROSSROAD_BASE_DIR . '/_config/site.yaml', true ) );
    }

    private function _checkConfig() {
        // check to make sure everything we need is here
    }

    private function _branding() {
        echo "\n----------------------------\n";
        echo "Crossroads " . CROSSROADS_VERSION . " starting up\n";
        echo "----------------------------\n";
    }

    private function _usage() {
        echo "..proper usage:\n\n";
        echo "php crossroads build                      Builds entire website\n";
        echo "php crossroads clean                      Cleans entire website\n";
        echo "php crossroads import wordpress <url>     Import from a WordPress website\n";
        echo "php crossroads serve                      Start webserver\n";
    }

    private function _import( $argc, $argv ) {
        if ( $argc == 4 ) {
            $importer = $argv[ 2 ];
            $url = $argv[ 3 ];

            if ( file_exists( 'core/src/importers/' . $importer . '.php' ) ) {
                require_once( 'core/src/importers/' . $importer . '.php' );

                $importer = new Importers\WordPress;
              //  $importer->import( Utils::fixPath( $url ) );
            } else {
                LOG( "Unknown importer [" . $importer . "]", 1, LOG::ERROR );
            }
        } else {
             $this->_usage();
        }
    }

    private function _build( $argc, $argv ) {
        LOG( "Starting static website build" );

        $this->builder = new Builder( $this->config );

        try {
            $this->builder->run();
        } catch( Exception $e ) {
            echo "..build stopped due to exception [" . $e->name() . "] with message [" . $e->msg() . "]\n";
        }
        
    }

    private function _clean( $argc, $argv ) {
        Utils::recursiveRmdir( CROSSROAD_PUBLIC_DIR );
    }

    private function _serve(  $argc, $argv ) {
        $server = new WebServer();
        $server->init();
        $server->serve();
    }
}