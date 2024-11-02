<?php

namespace CR;

class ImagePlugin extends Plugin {
    var $config = null;
    var $convert_to_webp = false;
    var $generate_responsive = false;

    public function __construct( $config ) {
        $this->config = $config;

        if ( isset( $this->config[ 'images' ] ) && isset( $this->config[ 'images' ][ 'convert_to_webp' ] ) ) {
            $this->convert_to_webp = $this->config[ 'images' ][ 'convert_to_webp' ];
        }

        if ( isset( $this->config[ 'images' ] ) && isset( $this->config[ 'images' ][ 'generate_responsive' ] ) ) {
            $this->generate_responsive = $this->config[ 'images' ][ 'generate_responsive' ];
        }
    }

    public function content_filter( $content ) {
        $regexp = '<img[^>]+src=(?:\"|\')\K(.[^">]+?)(?=\"|\')';
        $image_destination_path = CROSSROAD_PUBLIC_DIR . '/assets/' . $content->content_type;

        if( preg_match_all( "/$regexp/", $content->markdown_html, $matches, PREG_SET_ORDER ) ) {
            foreach( $matches as $images ) {
                $image_file = $images[ 0 ];

                $dest_url = $this->_find_and_fix_image( 
                    $image_file,
                    pathinfo( $content->markdown_file, PATHINFO_DIRNAME ),
                    '/assets/' . $content->content_type . '/' . date( 'Y', $content->publish_date ),
                    $content->publish_date,
                    $found_file
                );

                $content->markdown_html = str_replace( $image_file, $dest_url, $content->markdown_html );
            }
        }

        if ( $content->featured_image ) {
            $content->featured_image = $this->_find_and_fix_image( 
                $content->featured_image,
                pathinfo( $content->markdown_file, PATHINFO_DIRNAME ),
                '/assets/' . $content->content_type . '/' . date( 'Y', $content->publish_date ),
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

    private function _get_image_name_for_responsive( $destination_image, $destination_width ) {
       $image_ext = pathinfo( $destination_image, PATHINFO_EXTENSION );

       return str_replace( '.' . $image_ext, '-' . $destination_width . 'w.' . $image_ext, $destination_image );
    }

    private function _convert_image( $source_image, $destination_image, $force_width = false, $format_conversion = false ) {
        $souce_ext = pathinfo( $destination_image, PATHINFO_EXTENSION );
        $dest_ext = pathinfo( $destination_image, PATHINFO_EXTENSION );

        $image_data = false;
        $image_size = getimagesize( $source_image );

        switch( $souce_ext ) {
            case 'jpg':
            case 'jpeg':
                $image_data = imagecreatefromjpeg( $source_image );
                break;
            case 'gif':
                $image_data = imagecreatefromgif( $source_image );
                break;
            case 'webp';
                $image_data = imagecreatefromwebp( $source_image );
                break;
            case 'png';
                $image_data = imagecreatefrompng( $source_image );
                break;
            default:
                echo "............unknown image format\n";
                break;
        }

        if ( $image_data ) {
            if ( $force_width && $image_size[ 0 ] ) {
                // creating responsive image

                if ( $force_width < $image_size[ 0 ] ) {
                    // only resample if the image is larger than our target
                    $new_width = $force_width;
                    $new_height = floor( $force_width * $image_size[ 1 ] / $image_size[ 0 ] );

                    $new_image = imagecreatetruecolor( $new_width, $new_height );
                    imagecopyresampled( $new_image, $image_data, 0, 0, 0, 0, $new_width, $new_height, $image_size[ 0 ], $image_size[ 1 ] );

                    imagedestroy( $image_data );
                    $image_data = $new_image;
                } else {
                    imagedestroy( $image_data );
                    return false;
                }
            }

            echo "..............writing image [" . $destination_image . "]\n";

            if ( $format_conversion ) {
                imagepalettetotruecolor( $image_data );
                imageavif( $image_data, $destination_image );
            } else {
                switch( $dest_ext ) {
                    case 'jpg':
                    case 'jpeg':
                        imagejpeg( $image_data, $destination_image );
                        break;
                    case 'gif':
                        imagegif( $image_data, $destination_image );
                        break;
                    case 'webp';
                        imagewebp( $image_data, $destination_image );
                        break;
                    case 'png';
                        imagepng( $image_data, $destination_image );
                        break;
                    default:
                        echo "............unknown image format\n";
                        break;                
                }
            }

            imagedestroy( $image_data );
            return true;
        }

        return false;
    }

    private function _convert_or_copy_image( $source_image, $destination_image, $force_width = false, $format_conversion = false ) {
        $image_ext = pathinfo( $destination_image, PATHINFO_EXTENSION );
        if ( $format_conversion ) {
            // check to see if it's a jpg, otherwise disable conversion   
            if ( $image_ext != 'jpeg' && $image_ext != 'jpg' ) {
                $format_conversion = false;
            }
        }

        if ( $force_width || $format_conversion ) {
            // we have to process the image
            if ( $force_width ) {
                if ( $format_conversion ) {
                    $destination_image = str_replace( '.' . $image_ext, '.avif', $destination_image );
                    echo "............converting image to AVIF and width [" . $force_width . "]\n";
                } else {
                    echo "............converting image to width [" . $force_width . "]\n";
                }

                $destination_image = $this->_get_image_name_for_responsive( $destination_image, $force_width );
            } else if ( $format_conversion ) {
                $destination_image = str_replace( '.' . $image_ext, '.avif', $destination_image );
                echo "............converting image to AVIF\n";
            }       

            $result = $this->_convert_image( $source_image, $destination_image, $force_width, $format_conversion );      

            return ( $result ? $this->_get_image_information( $destination_image, false ) : $result );
        } else {
            // direct copy
            echo "..........copying image to [" . $destination_image . "]\n";
            Utils::copy_file( $source_image, $destination_image );

            return $this->_get_image_information( $destination_image, true );
        }
    }

    private function _is_remote_image( $image_file ) {
        if ( strpos( $image_file, 'http://' ) !== false || strpos( $image_file, 'https://' ) !== false ) {
            return true;
        }

        return false;
    }


    private function _get_image_information( $image_file, $include_resp = true, $is_remote = false ) {
        $image_info = new \stdClass;
        $image_info->width = false;
        $image_info->height = false;

        if ( $is_remote || $this->_is_remote_image( $image_file ) ) {
            $image_info->url = $image_file;
            $image_info->is_local = false;
            $image_info->path = false;

            $image_info->type = false;

            if ( $include_resp ) {
                $image_info->has_responsive = false;
                $image_info->responsive_images = false;
            }
           

        } else if ( file_exists( $image_file ) ) {
            $image_info = new \stdClass;
            $image_info->is_local = true;
            $image_info->path = $image_file;

            $image_size = getimagesize( $image_file );
            if ( $image_size ) {
                $image_info->width = $image_size[ 0 ];
                $image_info->height = $image_size[ 1 ];
            }

            $image_info->type = pathinfo( $image_file, PATHINFO_EXTENSION );
            $image_info->url = str_replace( CROSSROAD_PUBLIC_DIR, Utils::fix_path( $this->config[ 'site' ][ 'url'] ), $image_file );

            if ( $include_resp ) {
                $image_info->has_responsive = false;
                $image_info->responsive_images = [];
            }

            if ( $image_info->type == 'jpeg' ) {
                $image_info->type = 'jpg';
            }
        }   

        return $image_info;
    }

    private function _find_and_fix_image( $image_file, $current_path, $destination_path, $publish_date, &$found_file, $search_dirs = [ '', 'images/' ] ) {
        if ( $this->_is_remote_image( $image_file ) ) {
            echo "........skipping remote image [" . $image_file . "]\n";
            return $image_file;
        }

        $new_location = $image_file;

        foreach( $search_dirs as $search_dir ) {
            $convert_to_webp = false;

            $original_image_file = $search_dir . $image_file;
            $modified_image_file = $original_image_file;

            $image_ext = pathinfo( $modified_image_file, PATHINFO_EXTENSION );

            if ( ( $image_ext == 'jpg' || $image_ext == 'jpeg' ) && $this->convert_to_webp ) {
                $convert_to_webp = true;
                $modified_image_file = str_replace( '.' . $image_ext, '.webp', $modified_image_file );
            } else {
                $modified_image_file = $original_image_file;
            }

            $image_filename_only = pathinfo( $modified_image_file, PATHINFO_BASENAME );

            $image_destination_path_with_date = $destination_path;
            @mkdir( $image_destination_path_with_date );

            echo "........checking image " . $current_path . '/' . $original_image_file . "\n";

            // we have a valid source file
            if ( file_exists( $current_path . '/' . $original_image_file ) ) {  
                $destination_file = CROSSROAD_PUBLIC_DIR . $image_destination_path_with_date . '/' . $image_filename_only;
                $found_file = $current_path . '/' . $original_image_file;

                $main_image = $this->_convert_or_copy_image( $found_file, $destination_file );
                if ( $this->generate_responsive ) {
                    $image_400 = $this->_convert_or_copy_image( $found_file, $destination_file, 400 );
                    $image_640 = $this->_convert_or_copy_image( $found_file, $destination_file, 640 );
                    $image_800 = $this->_convert_or_copy_image( $found_file, $destination_file, 800 );

                    print_r( $main_image ); 
                    print_r( $image_400 );
                    print_r( $image_640 );
                    print_r( $image_800 );
                    
                    
                    die;

                    die;
                }

                /*

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

                $image_info = $this->_get_image_information( $destination_file );
               // print_r( $image_info );
               // die;

                $new_location = $this->config[ 'site' ][ 'url'] . $destination_path . '/' . $image_filename_only;
                */
                break;
            }
        }
  
        return $new_location;
    }    
}