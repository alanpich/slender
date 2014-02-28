<?php

function dump($mxd)
{
    echo "<pre>Debug:\n";
    $bt = debug_backtrace();
    $caller = array_shift($bt);
    echo "- {$caller['file']}::{$caller['line']}\n";
    echo "------------------------\n";
    foreach(func_get_args() as $arg){
        var_dump($arg);
    }
    echo "</pre>";
    die;
}

define('ROOT',dirname(__DIR__));
chdir(ROOT);
require dirname(dirname(__DIR__)).'/vendor/autoload.php';



$app = new Slender\App();



$app->run();