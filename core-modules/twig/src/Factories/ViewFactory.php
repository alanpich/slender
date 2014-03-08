<?php
namespace Slender\Module\Twig\Factories;

use Slender\App;
use Slender\Interfaces\FactoryInterface;
use Slender\Modules\Twig\View;

class ViewFactory implements FactoryInterface
{

    public function create(App $app)
    {
        $view = new \Slender\Module\Twig\View();
        $view->setTemplateDirs($app['settings']['view-paths']);
        return $view;
    }
}
