<?php

namespace CR;

class LogListenerShell extends LogListener {
    public function __construct() {}

    public function log( $message, $tabs, $level ) {
        $message = $this->getTabsAsSpaces( $tabs ) . $message;

        switch( $level ) {
            case Log::INFO:
                echo "\033[32;10m" . "[INFO] " . $message . "\033[0m\n";
                break;
            case Log::WARNING:
                echo "\033[33;10m" . "[WARN] " . $message . "\033[0m\n"; 
                break;
            case Log::ERROR:
                echo "\033[33;10m" . "[ERR ] " . $message . "\033[0m\n"; 
                break;    
        }
    }

    private function getTabsAsSpaces( $tabs ) {
        $spaces = '';
        for( $i = 0; $i < $tabs; $i++ ) {
            $spaces = $spaces . '  ';
        }

        return $spaces;
    }
}