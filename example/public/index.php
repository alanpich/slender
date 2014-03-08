<?php

define('ROOT',dirname(__DIR__));
chdir(ROOT);
require dirname(dirname(__DIR__)).'/vendor/autoload.php';


// This is because i can't be bothered to fix my environment
$whoops = new Whoops\Run();
$whoops->pushHandler(new Whoops\Handler\PrettyPageHandler());

// Set Whoops as the default error and exception handler used by PHP:
$whoops->register();




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



$app = new Slender\App();
$whoops->register();

$app->run();

?>
