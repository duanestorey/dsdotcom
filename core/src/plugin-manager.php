<?php

namespace CR;

class PluginManager extends Plugin {
    var $config = null;
    var $plugins = [];
    
    public function __construct( $config ) {
        $this->config = $config;
    }

    public function installPlugin( $plugin ) {
        $this->plugins[] = $plugin;
    }

    public function contentFilter( $content ) {
        if ( count( $this->plugins ) ) {
            foreach( $this->plugins as $plugin ) {
                $content = $plugin->contentFilter( $content );
            }
        }

        return $content;
    }

    public function templateParamFilter( $params ) {
        if ( count( $this->plugins ) ) {
            foreach( $this->plugins as $plugin ) {
                $params = $plugin->templateParamFilter( $params );
            }
        }

        return $params;
    }
} 