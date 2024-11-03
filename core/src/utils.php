<?php

namespace CR;

class Utils {
    static function fixPath( $dir ) {
        return rtrim( $dir, "\\/" );
    }

    static function copyFile( $source, $dest ) {
        copy( $source, $dest );
    }

    static function mkdir( $dirname ) {
        if ( !file_exists( $dirname ) ) {
            @mkdir( $dirname );
        }
    }

    static function cleanTerm( $term ) {
        return strtolower( str_replace( ' ', '-', $term ) );
    }

    static function findAllFilesWithExtension( $directory, $ext ) {
        $all_files = array();

        $filenames = array_diff( scandir( $directory ), array( '.', '..' ) );
        foreach( $filenames as $one_file ) {
            $full_path = $directory . '/' . $one_file;
            if ( is_dir( $full_path ) ) {
                $all_files = array_merge( $all_files, Utils::findAllFilesWithExtension( $full_path, $ext ) );
            } else if ( pathinfo( $full_path, PATHINFO_EXTENSION ) == $ext ) {
                $all_files[] = $full_path;
            }
        }

        return $all_files;
    }    
}