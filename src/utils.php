<?php

namespace CR;

class Utils {
    static function fix_path( $dir ) {
        return rtrim( $dir, "\\/" );
    }

    static function copy_file( $source, $dest ) {
        copy( $source, $dest );
    }
}