<?php

namespace CR;

abstract class LogListener {
     abstract function log( $message, $tabs, $level );
}