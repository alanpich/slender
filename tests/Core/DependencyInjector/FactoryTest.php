<?php
namespace Slender\Core\DependencyInjector;

use Slender\App;

class DummyApp extends \Slender\App {
    function __construct(){

    }
}

class FactoryTest extends \PHPUnit_Framework_TestCase
{

    public function testFactoryCreate()
    {
        $app = new DummyApp();

        $factory = new Factory;

        $obj = $factory->create($app);


        $this->assertInstanceOf('Slender\Core\DependencyInjector\DependencyInjector',$obj);

    }

} 
