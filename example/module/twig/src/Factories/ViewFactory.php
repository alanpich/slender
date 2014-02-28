<?php
namespace Slender\Modules\Twig\Factories;

class ViewFactory implements \Slender\FactoryInterface
{

    public function create(\Slender\App $app)
    {
        $view = new \Slender\Modules\Twig\View;

        // Set template paths
        $paths = $app['settings']['view-paths'];

        $view->setTemplateDirs($paths);

        return $view;
    }
}