<?php
/*
    All code copyright (c) 2024 by Duane Storey - All rights reserved
    You may use, distribute and modify this code under the terms of GPL version 3.0 license or later
*/
 
namespace CR;

class Theme {
    var $name = null;
    var $themeDir = null;
    var $config = null;

    public function __construct( $name, $themeDir ) {
        $this->name = $name;
        $this->themeDir = $themeDir . '/' . $name;
    }

    public function name() {
        return $this->name;
    }

    public function isSane() {
        // check theme sanity
        if ( file_exists( $this->themeDir . '/theme.yaml' ) && file_exists( $this->themeDir . '/home.latte' ) ) {
            return true;
        }

        return false;
    }

    public function getAssetHash() {
        $hashValue = 0;

        foreach( $this->config->get( 'theme.assets', [] ) as $destName => $sources ) {
            if ( file_exists( CROSSROADS_PUBLIC_DIR . '/assets/' . $destName ) ) {
                $hashValue = $hashValue . filesize( CROSSROADS_PUBLIC_DIR . '/assets/' . $destName );
            }
        }

        return md5( $hashValue );
    }

    public function loadConfig() {
        if ( $this->config == null && file_exists( $this->themeDir . '/theme.yaml' ) ) {
            $this->config = new Config( YAML::parse_file( $this->themeDir . '/theme.yaml', true ) );
        }
    }

    public function processAssets( $destination_dir ) {
        foreach( $this->config->get( 'theme.assets', [] ) as $destName => $sources ) {
            LOG( sprintf( _i18n( 'core.class.theme.processing' ), $destName ), 3, LOG::DEBUG );

            $content = '';
            
            foreach( $sources as $key => $source ) {
                if ( file_exists( $this->themeDir . '/assets/' . $source ) ) {
                    
                    LOG( sprintf( _i18n( 'core.class.theme.adding' ), $source ), 4, LOG::DEBUG );
                    $content = $content . "\n\n" . file_get_contents( $this->themeDir . '/assets/' . $source );
                } else {
                    LOG( sprintf( _i18n( 'core.class.theme.no_source' ), $this->themeDir . '/assets/' . $source ), 4, LOG::WARNING );
                }
            }

            LOG( "Writing static file [" . $destName . "]", 4, LOG::DEBUG );

            file_put_contents( CROSSROADS_PUBLIC_DIR . '/assets/' . $destName, $content );
        }

        foreach( $this->config->get( 'theme.images', '[]' ) as $imageFile ) {
            if ( file_exists( $this->themeDir . '/assets/' . $imageFile ) ) {
                Utils::copyFile( $this->themeDir . '/assets/' . $imageFile, CROSSROADS_PUBLIC_DIR . '/assets/' . pathinfo( $imageFile, PATHINFO_BASENAME ) );

                LOG( sprintf( _i18n( 'core.class.theme.copying' ), $imageFile, CROSSROADS_PUBLIC_DIR . '/assets/' . pathinfo( $imageFile, PATHINFO_BASENAME ) ), 3, LOG::DEBUG );
            }
        }
    }
}