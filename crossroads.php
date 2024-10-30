<?php 

require_once 'vendor/autoload.php';
require_once 'src/crossroads.php';

$crossroads = new CR\Engine;
$crossroads->run( $argc, $argv );


