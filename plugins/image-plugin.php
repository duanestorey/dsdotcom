<?php

namespace CR;

class ImagePlugin extends Plugin {
    var $config = null;

    public function __construct( $config ) {
        $this->config = $config;
    }

    public function content_filter( $content ) {
        $regexp = '<img[^>]+src=(?:\"|\')\K(.[^">]+?)(?=\"|\')';
        $image_destination_path = CROSSROAD_PUBLIC_DIR . '/assets/' . $content->content_type;

        if( preg_match_all( "/$regexp/", $content->markdown_html, $matches, PREG_SET_ORDER ) ) {
            foreach( $matches as $images ) {
                $image_file = $images[ 0 ];

                $dest_url = $this->_find_and_fix_image( 
                    $content->content_type,
                    $image_file,
                    pathinfo( $content->markdown_file, PATHINFO_DIRNAME ),
                    $image_destination_path,
                    $content->publish_date,
                    $found_file
                );

                $content->markdown_html = str_replace( $image_file, $dest_url, $content->markdown_html );
            }
        }

        if ( $content->featured_image ) {
            $content->featured_image = $this->_find_and_fix_image( 
                $content->content_type,
                $content->featured_image,
                pathinfo( $content->markdown_file, PATHINFO_DIRNAME ),
                $image_destination_path,
                $content->publish_date,
                $found_file
            );

            if ( $found_file ) {
                $image_info = getimagesize( $found_file );
                $content->featured_image_width = $image_info[ 0 ];
                $content->featured_image_height = $image_info[ 1 ];
            }
        }

        return $content;
    }    

    public function template_param_filter( $params ) {
        return $params;
    }

    private function _find_and_fix_image( $content_type, $image_file, $current_path, $destination_path, $publish_date, &$found_file, $search_dirs = [ '', 'images/' ] ) {
        $new_location = $image_file;

        foreach( $search_dirs as $search_dir ) {
            $convert_to_webp = false;

            $original_image_file = $search_dir . $image_file;
            $modified_image_file = $original_image_file;

            $image_ext = pathinfo( $modified_image_file, PATHINFO_EXTENSION );

            if ( ( $image_ext == 'jpg' || $image_ext == 'jpeg' ) ) {
                $convert_to_webp = true;
                $modified_image_file = str_replace( '.' . $image_ext, '.webp', $modified_image_file );
            } else {
                $modified_image_file = $original_image_file;
            }

            $image_filename_only = pathinfo( $modified_image_file, PATHINFO_BASENAME );

            $image_destination_path_with_date = $destination_path . '/' . date( 'Y', $publish_date );
            @mkdir( $image_destination_path_with_date );

            echo "........checking image " . $current_path . '/' . $original_image_file . "\n";

            if ( file_exists( $current_path . '/' . $original_image_file ) ) {  
                $destination_file = $image_destination_path_with_date . '/' . $image_filename_only;

                if ( !file_exists( $destination_file ) ) {
                    if ( $convert_to_webp ) {
                        echo "........converting image [" . $original_image_file . "] to [" . $destination_file . "]\n";

                        $image = false;
                        if ( $image_ext == 'jpg' || $image_ext == 'jpeg' ) {
                            $image = imagecreatefromjpeg( $current_path . '/' . $original_image_file );
                        } 
                        
                        if ( $image ) {
                            imagewebp( $image, $destination_file, 85 );
                            imagedestroy( $image );
                        }
                    } else {
                        echo "........copying image [" . $image_filename_only . "] to [" . $destination_file . "]\n";
                        Utils::copy_file( $current_path . '/' . $original_image_file, $destination_file );   
                    }             
                }

                $new_location = $this->config[ 'site' ][ 'url'] . '/assets/' . $content_type . '/' . date( 'Y', $publish_date ) . '/' . $image_filename_only;
                $found_file = $current_path . '/' . $original_image_file;
                break;
            }
        }
  
        return $new_location;
    }    
}