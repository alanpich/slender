<?php
namespace Slender\Module\EventManager;

use Slender\Interfaces\FactoryInterface;

class Factory implements FactoryInterface
{

    public function create(\Slender\App $app)
    {
        $em = new EventManager($app);

        return $em;
    }
}