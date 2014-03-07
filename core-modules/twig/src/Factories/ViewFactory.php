<?php
namespace Slender\Module\Twig\Factories;

use Slender\App;
use Slender\Interfaces\FactoryInterface;
use Slender\Modules\Twig\View;

class ViewFactory implements FactoryInterface
{

    public function create(App $app)
    {
        try {
            $view = new View
        }catch(\Exception $E){
            echo "> I CAUGHT AN ERROR\n";
        }

        // Set template paths
        $paths = $app['settings']['view-paths'];

        $view->setTemplateDirs($paths);

        return $view;
    }
}
