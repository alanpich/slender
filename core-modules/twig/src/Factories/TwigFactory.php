<?php
namespace Slender\Module\Twig\Factories;

use Slender\Interfaces\FactoryInterface;

class TwigFactory implements FactoryInterface
{

    /**
     * @param \Slender\App $app
     * @return \Twig_Environment
     */
    public function create(\Slender\App $app)
    {
        \Twig_Autoloader::register();

        // Application settings
        $appConf = $app['settings'];

        // Get all registered template paths
        $paths = $app['settings']['view-paths'];
        $loader = new \Twig_Loader_Filesystem($paths);

        // Get Twig_Environment settings
        $options = array_merge(array(
            'charset'          => $appConf['charset'],
            'debug'            => $appConf['debug'],
            'strict_variables' => false,//$appConf['debug'],
        ),$appConf['twig']['environment']);

        // Create twig instance and loader
        $twig = new \Twig_Environment($loader, $options);

        // Additional Debug helpers
        if ($appConf['debug']) {
            $twig->addExtension(new \Twig_Extension_Debug());
        }

        // Register additional extensions
        $extensions = $appConf['twig']['extensions'];
        foreach($extensions as $class){
            $twig->addExtension( $app['dependency-injector']->create($class) );
        }

        // Here you go!
        return $twig;
    }
}
