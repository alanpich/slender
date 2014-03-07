<?php
namespace Slender\Module\ServiceManager;

use Slender\Interfaces\FactoryInterface;
use Slender\Module\DependencyInjector\DependencyInjector;

class ServiceManager
{
    /** @var \Pimple  */
    protected $diContainer;

    /** @var  DependencyInjector */
    protected $diInjector;

    public function registerService($identifier,$class)
    {
        $this->diContainer[$identifier] = $this->getServiceCallable($class);
    }

    public function registerFactory($identifier,$class)
    {
        $this->diContainer[$identifier] = $this->diContainer->factory(
            $this->getServiceCallable($class)
        );

    }


    /**
     * @param string $class Class to be instantiated
     * @return callable
     */
    protected function getServiceCallable( $class )
    {
        return function() use($class) {
            $inst = new $class;
            if ($inst instanceof FactoryInterface) {
                // FactoryInterface style
                return $inst->create($this->diContainer);
            } else {
                // Regular class - check for depInjection
                $this->diInjector->prepare($inst);
                return $inst;
            }
        };
    }

    /**
     * @param \Pimple $diContainer
     */
    public function setDiContainer($diContainer)
    {
        $this->diContainer = $diContainer;
    }

    /**
     * @return \Pimple
     */
    public function getDiContainer()
    {
        return $this->diContainer;
    }

    /**
     * @param \Slender\Module\DependencyInjector\DependencyInjector $diInjector
     */
    public function setDiInjector($diInjector)
    {
        $this->diInjector = $diInjector;
    }

    /**
     * @return \Slender\Module\DependencyInjector\DependencyInjector
     */
    public function getDiInjector()
    {
        return $this->diInjector;
    }




}
