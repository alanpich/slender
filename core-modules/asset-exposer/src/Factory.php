<?php
namespace Slender\Module\AssetExposer;

use Slender\Interfaces\FactoryInterface;

class Factory implements FactoryInterface
{

    public function create(\Slender\App $app)
    {
        $assetExposer = new AssetExposer();

        // Construct hash of object names to paths
        $modules = array();
        $conf = $app['settings'];

        $aeConf = $app['settings']['asset-exposer'];
        $assetExposer->setConfig($aeConf);

        // Get the module config
        $assetExposer->setModuleConfig($conf['module-config']);

        // Set 404 callable
        $assetExposer->setNotFoundCallable(function() use ($app){
               $app->notFound();
            });

        $assetExposer->setReturnFileCallable(function($path) use ($app){
                $app->sendFile($path);
            });


        return $assetExposer;
    }
}
