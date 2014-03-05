<?php
define('ROOT',dirname(dirname(__DIR__)));
// Read package.json
$pkg = json_decode(file_get_contents(ROOT.'/composer.json'));
$currentVersion = $pkg->version;

$bits = explode('.',$currentVersion);
$major = (int) $bits[0];
$minor = (int) $bits[1];
$patch = (int) $bits[2];

array_shift($argv);


if(isset($argv[0])){
    $cmd = $argv[0];
    if(substr($cmd,0,2) == '++'){
        // Increment a value
        $which = trim(substr($cmd,2));
        switch($which){
            case 'major':
                ++$major;
                break;
            case 'minor':
                ++$minor;
                break;
            case 'patch':
                ++$patch;
                break;
        }
    }
}

echo "{$major}.{$minor}.{$patch}";
