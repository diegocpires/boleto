<?php
$autoload = __DIR__ . '/vendor/autoload.php';
define('CONFIG_DIR', __DIR__ );

require $autoload;

$isDevMode = true;
// var_dump($isDevMode);

if ($isDevMode) {
    error_reporting(-1);
    //error_reporting("E_ALL");
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', TRUE);
}
date_default_timezone_set("America/Sao_Paulo");