<?php

namespace CR;

class LogListenerShell extends LogListener {
    protected $currentLevel = LOG::INFO;

    public function __construct() {}

    public function setLevel( $level ) {
        $this->currentLevel = $level;
    }

    public function log( $message, $tabs, $level ) {
        if ( $level < $this->currentLevel ) {
            return;
        }

        $message = $this->getTabsAsSpaces( $tabs ) . $message;

        switch( $level ) {
            case Log::DEBUG:
                echo "\033[90;10m" . "[DEBUG] " . $message . "\033[0m\n";
                break;
            case Log::INFO:
                echo "\033[92;10m" . "[INFO ] " . $message . "\033[0m\n";
                break;
            case Log::WARNING:
                echo "\033[33;10m" . "[WARN ] " . $message . "\033[0m\n"; 
                break;
            case Log::ERROR:
                echo "\033[91;10m" . "[ERROR] " . $message . "\033[0m\n"; 
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