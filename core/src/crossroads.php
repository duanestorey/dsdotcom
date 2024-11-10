<?php
/*
    All code copyright (c) 2024 by Duane Storey - All rights reserved
    You may use, distribute and modify this code under the terms of GPL version 3.0 license or later
*/

namespace CR;

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
require_once( 'international.php' );
require_once( 'upgrade.php' );
require_once( 'log.php' );
require_once( 'config.php' );
require_once( 'web-server.php' );
require_once( 'log-listener.php' );
require_once( 'log-listener-shell.php' );
require_once( 'log-listener-file.php' );

require_once( CROSSROADS_CORE_DIR . '/plugins/image-plugin.php' );
require_once( CROSSROADS_CORE_DIR . '/plugins/seo-plugin.php' );
require_once( CROSSROADS_CORE_DIR . '/plugins/wordpress-plugin.php' );

class Engine {
    var $builder = null;
    var $config = null;
    var $startTime = null;
    var $fileLog = null;

    public function __construct() {
    }

    public function run( $argc, $argv ) {
        $this->_loadConfig();
        $this->_setupLocales();

        if ( $argc <= 1 ) {
            $this->_branding();
            $this->_usage();
        } else {
            $command = $argv[ 1 ];

            $foundCommand = false;
            $allowableCommands = $this->_getAllowableCommands();
            $this->_branding();

            foreach( $allowableCommands as $oneCommand => $required_params ) {
                if ( $command == $oneCommand ) {
                    // right command, let's check params
                    if ( $argc != ( $required_params + 2 ) ) {
                        $this->_usage();
                    } else {    
                        $foundCommand = true;

                        // we are good to go
                        Log::instance()->installListener( new LogListenerShell() );

                        if ( $this->_checkInit() || $command == 'init' ) {  
                            $this->_setupFileLogs( $command );

                            LOG( sprintf( _i18n( 'core.app.exec_command' ), strtoupper( $argv[ 1 ] ), date( 'Y-m-d' ), date( 'H:i:s' ) ), 0, LOG::INFO );

                            $this->startTime = microtime( true );

                            $function = '_' . $command;

                            $this->{$function}( $argc, $argv );

                            LOG( sprintf( _i18n( 'core.app.finished' ), strtoupper( $argv[ 1 ] ), microtime( true ) - $this->startTime ), 0, LOG::INFO );
                        } else {
                            LOG( _i18n( 'core.usage.need_init' ), 0, LOG::WARNING );
                        }

                        echo "\n";
                    }
                }
            }

            if ( !$foundCommand ) {
                $this->_usage();
            }
        }
    }

    private function _setupFileLogs( $command ) {
        Utils::mkdir( CROSSROADS_LOG_DIR );
        $logSlug = date( 'Y-m-d' ) . '-' . $command . '.log';
        $logfile = CROSSROADS_LOG_DIR . '/' . $logSlug;

        $this->fileLog = new LogListenerFile( $logfile );
        $this->fileLog->setLevel( LOG::INFO );
        Log::instance()->installListener( $this->fileLog );

        // Add debug logs if debug is enabled
        if ( $this->config->get( 'options.debug' ) ) {
            $debugLog = new LogListenerFile( CROSSROADS_LOG_DIR . '/' . date( 'Y-m-d' ) . '-' . $command . '-debug.log' );
            $debugLog->setLevel( LOG::DEBUG );
            Log::instance()->installListener( $debugLog );
        }
        
        LOG( sprintf( _i18n( 'core.app.log' ), CROSSROADS_LOG_SLUG . '/' . $logSlug ), 0, LOG::INFO );    
    }

    private function _newGetContentType( $singularOrPlural ) {
        foreach( $this->config->get( 'content' ) as $contentType => $contentConfig ) {
            if ( ( $contentType == $singularOrPlural ) || ( $singularOrPlural == $this->config->get( 'content.' . $contentType . '.singular' ) ) ) {
                return $contentType;
            }
        }
    }

    private function _init( $argc, $argv ) {    
        if ( $this->_checkInit() ) {
            LOG( _i18n( 'core.init.not_needed' ), 1, LOG::INFO );
            return;
        }

        LOG( _i18n( 'core.init.starting' ), 0, LOG::INFO );

        LOG( _i18n( 'core.init.git' ), 1, LOG::INFO );
        if ( !file_exists( CROSSROADS_BASE_DIR . '/.gitignore' ) ) {
            // write git file
            $gitContents = "vendor\n";
            file_put_contents( CROSSROADS_BASE_DIR . '/.gitignore', $gitContents );
        }

        LOG( _i18n( 'core.init.version' ), 1, LOG::INFO );
        file_put_contents( CROSSROADS_BASE_DIR . '/.crossroadsinit', CROSSROADS_VERSION );

        LOG( _i18n( 'core.init.done' ), 0, LOG::INFO );
    }

    private function _new( $argc, $argv ) {
        $contentSingular = $argv[ 2 ];
        $contentType = $this->_newGetContentType( $contentSingular );
        
        if ( $contentType ) {
            LOG( sprintf( _i18n( 'core.build.processing.content' ), $contentSingular ), 1, LOG::INFO );
            echo "  " . _i18n( 'core.new.title' );
            $s = readline();

            if ( $s ) {
                $slug = Utils::titleToSlug( $s );
                $now = date( 'Y-m-d' );

                $content =  "---\n";
                $content .= "title: \"" . $s . "\"\n";
                $content .= "publishDate: \"" . $now . "\"\n";
                $content .= "slug: \"" . $slug . "\"\n";

                $taxonomies = $this->config->get( 'content.' . $contentType . '.taxonomy', [] );
                foreach( $taxonomies as $tax ) {
                    $content .= $tax . ":\n";
                }

                $content .= "---\n\n";
                $content .= _i18n( 'core.new.start' ) . "\n";

                if ( $this->config->get( 'content.' . $contentType . '.include_date', false ) ) {
                    $slug = $now . '-' . $slug; 
                } 

                $markdownFile = $contentType . '/' . $slug . '.md';

                LOG( sprintf( _i18n( 'core.new.created' ), $contentSingular, CROSSROADS_CONTENT_SLUG . '/' . $markdownFile ), 1, LOG::INFO );

                file_put_contents( CROSSROADS_CONTENT_DIR . '/' . $markdownFile, $content );

                $openCommand = $this->config->get( 'options.markdown.open_command' );
                if ( $openCommand && $this->config->get( 'options.markdown.auto' ) ) {
                    exec( sprintf( $openCommand, CROSSROADS_CONTENT_DIR . '/' . $markdownFile ) );
                } 
            }                  
        } else {
            LOG( sprintf( _i18n( 'core.new.unknown' ), $contentSingular ), 0, LOG::ERROR );
        }
    }

    private function _getAllowableCommands() {
        return array(
            'build' => 0,
            'import' => 2,
            'serve' => 0,
            'clean' => 0,
            'new' => 1,
            'init' => 0,
            'upgrade' => 0
        );
    }

    private function _setupLocales() {
        $currentLocale = $this->config->get( 'site.lang' );
        if ( $currentLocale ) {
            $localeFile = CROSSROADS_LOCALE_DIR . '/' . $currentLocale . '.yaml';
            if ( file_exists( $localeFile ) ) {
                International::instance()->loadLocaleFile( $localeFile );
            }
        }
    }

    private function _loadConfig() {
        $this->config = new Config( YAML::parse_file( CROSSROADS_CONFIG_DIR . '/site.yaml', true ) );
    }

    private function _checkConfig() {
        // check to make sure everything we need is here
    }

    private function _upgrade() {
        $upgrade = new Upgrade( $this->config );
        $upgrade->runUpgrader();
    }

    private function _branding() {
        $brandAndVersion = '| ' . sprintf( _i18n( 'core.app.starting' ), 'Crossroads', CROSSROADS_VERSION ) . ' |';
        $header = '';
        for( $i = 0; $i < mb_strlen( $brandAndVersion ); $i++ ) {
            $header .= '-';
        }

        echo "\n";
        echo $header . "\n";
        echo $brandAndVersion . "\n";
        echo $header . "\n";    
    }

    private function _usage() {
        $spacing = "%-50s";

        echo _i18n( 'core.usage.proper' ) . "\n\n";
        echo sprintf( $spacing, "php crossroads build" ) . _i18n( 'core.usage.build') . "\n";
        echo sprintf( $spacing, "php crossroads clean" ) . _i18n( 'core.usage.clean') . "\n";
        echo sprintf( $spacing, "php crossroads create plugin" ) . _i18n( 'core.usage.create.plugin') . "\n";
        echo sprintf( $spacing, "php crossroads create theme" ) . _i18n( 'core.usage.create.theme') . "\n";
        echo sprintf( $spacing, "php crossroads create child-theme" ) . _i18n( 'core.usage.create.child') . "\n";
        echo sprintf( $spacing, "php crossroads import wordpress <url>" ) . _i18n( 'core.usage.import.wordpress') . "\n";
        echo sprintf( $spacing, "php crossroads init" ) . _i18n( 'core.usage.init') . "\n";

        foreach ( $this->config->get( 'content', [] ) as $contentType => $configData ) {
            echo sprintf( $spacing, sprintf( _i18n( 'core.usage.new.cmd' ), $this->config->get( 'content.' . $contentType . '.singular', $contentType ) ) );
            echo sprintf( _i18n( 'core.usage.new.content' ) . "\n", $contentType );
        }

        echo sprintf( $spacing, "php crossroads serve" ) . _i18n( 'core.usage.serve' ) . "\n";
        echo sprintf( $spacing, "php crossroads upgrade" ) . _i18n( 'core.usage.upgrade' ) . "\n";
    }

    private function _import( $argc, $argv ) {
        if ( $argc == 4 ) {
            $importer = $argv[ 2 ];
            $url = $argv[ 3 ];

            if ( file_exists( 'core/src/importers/' . $importer . '.php' ) ) {
                require_once( 'core/src/importers/' . $importer . '.php' );

                $importer = new Importers\WordPress;
                $importer->import( Utils::fixPath( $url ) );
            } else {
                LOG( sprintf( _i18n( 'core.import.unknown' ), $importer ), 1, LOG::ERROR );
            }
        } else {
             $this->_usage();
        }
    }

    private function _build( $argc, $argv ) {
        LOG( _i18n( 'core.build.starting' ) );

        $this->builder = new Builder( $this->config );

        try {
            $this->builder->run();
        } catch( Exception $e ) {
            LOG( sprintf( _i18n( 'core.app.exception' ), $e->name(), $e->msg() ), 0, LOG::ERROR );
        }
        
    }

    private function _clean( $argc, $argv ) {
        Utils::recursiveRmdir( CROSSROADS_PUBLIC_DIR );
    }

    private function _checkInit() {
        return ( file_exists( CROSSROADS_BASE_DIR . '/.crossroadsinit' ) );
    }

    private function _serve(  $argc, $argv ) {
        $server = new WebServer();
        $server->init();

        $openCommand = $this->config->get( 'options.browser.open_command' );
        if ( $openCommand && $this->config->get( 'options.browser.auto' ) ) {
            exec( sprintf( $openCommand, 'http://' . $server->addressAndPort() ) );
        } 

        LOG( _i18n( 'core.server.to_close' ), 1, LOG::INFO );

        $server->serve();
    }
}