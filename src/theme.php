<?php
 
namespace CR;

class Theme {
    var $name = null;
    var $theme_dir = null;
    var $config = null;

    public function __construct( $name, $theme_dir ) {
        $this->name = $name;
        $this->theme_dir = $theme_dir . '/' . $name;
    }

    public function name() {
        return $this->name;
    }

    public function is_sane() {
        // check theme sanity
        if ( file_exists( $this->theme_dir . '/theme.yaml' ) && file_exists( $this->theme_dir . '/home.latte' ) ) {
            return true;
        }

        return false;
    }

    public function load_config() {
        if ( $this->config == null && file_exists( $this->theme_dir . '/theme.yaml' ) ) {
            $this->config = YAML::parse_file( $this->theme_dir . '/theme.yaml' );
        }
    }

    public function process_assets( $destination_dir ) {
        if ( isset( $this->config[ 'theme' ][ 'assets' ] ) ) {
            foreach( $this->config[ 'theme' ][ 'assets' ] as $dest_name => $sources ) {
                echo "......processing asset [" . $dest_name . "]\n";

                $content = '';
                
                foreach( $sources as $key => $source ) {
                    if ( file_exists( $this->theme_dir . '/assets/' . $source ) ) {
                        
                        echo "........adding file [" . $source . "]\n";
                        $content = $content . file_get_contents( $this->theme_dir . '/assets/' . $source );
                    } else {
                        echo "........unable to find source [" . $this->theme_dir . '/assets/' . $source . "]\n";
                    }
                }

                echo "....writing static file [" . $dest_name . "]\n";
                file_put_contents( CROSSROAD_PUBLIC_DIR . '/assets/' . $dest_name, $content );
            }
        }
    }
}