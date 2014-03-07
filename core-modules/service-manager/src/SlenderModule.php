<?php
namespace Slender\Module\ServiceManager;

use Slender\Interfaces\ModuleInvokableInterface;
use Slender\Interfaces\ModulePathProviderInterface;

class SlenderModule implements ModulePathProviderInterface,
                               ModuleInvokableInterface
{

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
        $app['service-manager'] = $serviceManager = new ServiceManager($app);
        $serviceManager->setDiContainer($app);
        $serviceManager->setDiInjector($app['dependency-injector']);

        $conf = $app['settings'];

        foreach($conf['services'] as $identifier => $class){
            $serviceManager->registerService($identifier,$class);

        }

        foreach($conf['factories'] as $identifier => $class){
            $serviceManager->registerFactory($identifier,$class);
        }
    }
}
