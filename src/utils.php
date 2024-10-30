<?php

namespace CR;

class Utils {
    static function fix_path( $dir ) {
        return rtrim( $dir, "\\/" );
    }
}