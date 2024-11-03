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

        if ( isset( $this->config[ 'theme' ][ 'assets' ] ) ) {
            foreach( $this->config[ 'theme' ][ 'assets' ] as $destName => $sources ) {
                if ( file_exists( CROSSROAD_PUBLIC_DIR . '/assets/' . $destName ) ) {
                    $hashValue = $hashValue . filesize( CROSSROAD_PUBLIC_DIR . '/assets/' . $destName );
                }
            }
        }

        return md5( $hashValue );
    }

    public function loadConfig() {
        if ( $this->config == null && file_exists( $this->themeDir . '/theme.yaml' ) ) {
            $this->config = YAML::parse_file( $this->themeDir . '/theme.yaml' );
        }
    }

    public function processAssets( $destination_dir ) {
        if ( isset( $this->config[ 'theme' ][ 'assets' ] ) ) {
            foreach( $this->config[ 'theme' ][ 'assets' ] as $destName => $sources ) {
                echo "......processing asset [" . $destName . "]\n";

                $content = '';
                
                foreach( $sources as $key => $source ) {
                    if ( file_exists( $this->themeDir . '/assets/' . $source ) ) {
                        
                        echo "........adding file [" . $source . "]\n";
                        $content = $content . "\n\n" . file_get_contents( $this->themeDir . '/assets/' . $source );
                    } else {
                        echo "........unable to find source [" . $this->themeDir . '/assets/' . $source . "]\n";
                    }
                }

                echo "....writing static file [" . $destName . "]\n";
                file_put_contents( CROSSROAD_PUBLIC_DIR . '/assets/' . $destName, $content );
            }
        }

        if ( isset( $this->config[ 'theme' ][ 'images' ] ) ) {
            foreach( $this->config[ 'theme' ][ 'images' ] as $imageFile ) {
                if ( file_exists( $this->themeDir . '/assets/' . $imageFile ) ) {
                    Utils::copy_file( $this->themeDir . '/assets/' . $imageFile, CROSSROAD_PUBLIC_DIR . '/assets/' . pathinfo( $imageFile, PATHINFO_BASENAME ) );

                    echo "....copying static image file [" . $imageFile . "] to [" . CROSSROAD_PUBLIC_DIR . '/assets/' . pathinfo( $imageFile, PATHINFO_BASENAME ) . "\n";
                }
            }
        }
    }
}