<?php
namespace Slender\Modules\Twig\Factories;

use Slender\App;
use Slender\Interfaces\FactoryInterface;
use Slender\Modules\Twig\View;

class ViewFactory implements FactoryInterface
{

    public function create(App $app)
    {
        $view = new View;

        // Set template paths
        $paths = $app['settings']['view-paths'];

        $view->setTemplateDirs($paths);

        return $view;
    }
}