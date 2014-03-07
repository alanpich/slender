<?php

namespace Slender\Core\ModuleLoader;

use Slender\Core\Autoloader\ClassLoader;
use Slender\Core\ModuleResolver\DirectoryResolver;
use \Mockery as m;

class ModuleLoaderTest extends \PHPUnit_Framework_TestCase
{

    public function tearDown()
    {
        m::close();
    }


    public function testSetResolver()
    {
        $obj = new ModuleLoader();
        $resolver = new DirectoryResolver('.');

        $obj->setResolver($resolver);
        $this->assertAttributeNotEmpty('resolver',$obj);
        $this->assertAttributeEquals($resolver,'resolver',$obj);
    }


    public function testSetConfig()
    {
        $obj = new ModuleLoader();
        $config = new \Slim\Configuration(new \Slim\ConfigurationHandler);

        $obj->setConfig($config);
        $this->assertAttributeNotEmpty('config',$obj);
        $this->assertAttributeEquals($config,'config',$obj);
    }


    public function testGetClassloader()
    {
        $obj = new ModuleLoader();
        $classLoader = new ClassLoader();
        $obj->setClassLoader($classLoader);

        $A = $obj->getClassLoader();

        $this->assertInstanceOf('Slender\Core\Autoloader\ClassLoader',$A);
        $this->assertEquals($classLoader,$A);
    }


    public function testSetupAutoloaders()
    {
        $obj = new ModuleLoader();

        // Make protected method accessible
        $refl = new \ReflectionClass($obj);
        $method = $refl->getMethod('setupAutoloaders');
        $method->setAccessible(true);

        // Create dummy ClassLoader
        $mockClassloader = m::mock('Slender\Core\Autoloader\ClassLoader');
        $mockClassloader
            ->shouldReceive('registerNamespace')
            ->withArgs(['Foo','./foo/bar','psr-4'])
            ->once();
        $obj->setClassLoader($mockClassloader);

        // Dummy data
        $dummyModulePath = './foo';
        $dummyMConf = array(
            'autoload' => array(
                'psr-4' => array(
                    'Foo' => 'bar'
                )
            ),
        );

        $method->invoke($obj,$dummyModulePath,$dummyMConf);
    }

    public function testAddConfig()
    {
        $obj = new ModuleLoader();

        // Config object
        $config = new \Slim\Configuration(new \Slim\ConfigurationHandler());
        $config['foo'] = 'FOO';
        $config['bar-array'] = ['a','b'];
        $obj->setConfig($config);

        // Our new config
        $testConf = array(
            'foo' => 'BAR',
            'bar-array' => ['c'],
            'new-key' => 'hello'
        );

        $obj->addConfig($testConf);


        // Arrays should merge
        $this->assertSame(['a','b','c'], $config['bar-array'],
            "Arrays are merged");

        // Simples should overwrite
        $this->assertEquals('BAR',$config['foo'],
            "Basic types are overwritten");

        // Nonexistant keys are ok
        $this->assertArrayHasKey('new-key',$config);
        $this->assertEquals('hello',$config['new-key']);
    }


    public function testLoadModule()
    {
        $obj = new ModuleLoader();

        // Make $loadedModules accessible
        $refl = new \ReflectionClass($obj);
        $pLoadedModules = $refl->getProperty('loadedModules');
        $pLoadedModules->setAccessible(true);



    }


}
