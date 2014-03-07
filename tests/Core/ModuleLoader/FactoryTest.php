<?php
namespace Slender\Core\ModuleLoader;

use Slender\Core\ModuleResolver\DirectoryResolver;

class MockApp extends \Slender\App {
    function __construct(){
        $this['module-resolver'] = new DirectoryResolver('./');
        $this['settings'] = new \Slim\Configuration(new \Slim\ConfigurationHandler());
        $this['autoloader'] = 'FAR';
    }
}

class FactoryTest extends \PHPUnit_Framework_TestCase
{

    public function testCreate()
    {
        $factory = new Factory();
        $app = new MockApp();

        $obj = $factory->create($app);

        $this->assertNotEmpty($obj);
        $this->assertInstanceOf('Slender\Core\ModuleLoader\ModuleLoader',$obj);

        $this->assertAttributeNotEmpty('resolver',$obj);
        $this->assertAttributeNotEmpty('config',$obj);
        $this->assertAttributeNotEmpty('classLoader',$obj);

    }

} 
