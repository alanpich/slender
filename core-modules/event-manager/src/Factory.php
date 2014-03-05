<?php
namespace Slender\Module\EventManager;

use Slender\Interfaces\FactoryInterface;

class Factory implements FactoryInterface
{

    public function create(\Slender\App $app)
    {
        $eventManager = new EventManager($app);

        return $eventManager;
    }
}