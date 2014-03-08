<?php
namespace Slender\Module\AssetExposer;

use Slender\Interfaces\ModuleInvokableInterface;
use Slender\Interfaces\ModulePathProviderInterface;

class SlenderModule implements ModulePathProviderInterface,
                               ModuleInvokableInterface
{

    /** @var  \Slender\App */
    private $app;

    /**
     * Returns path to module root
     *
     * @return string Path
     */
    public static function getModulePath()
    {
        return dirname(__DIR__);
    }


    public function invoke(\Slender\App &$app)
    {
        $this->app = $app;
        // Get route to use
        $pattern = $app['settings']['asset-exposer']['route'];
        // Register a new route to handle modules
        $app->get($pattern,[$this,'handleAssetRequest']);
    }


    public function handleAssetRequest($module,$path)
    {
        /** @var AssetExposer $assetExposer */
        $assetExposer = $this->app['asset-exposer'];
        $assetExposer->serveAsset($module,$path);
    }
}
