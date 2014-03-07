<?php
namespace Slender\Core\DependencyInjector;

use Slender\Core\DependencyInjector\Annotation as Slender;


class DummyClassForTesting
{

    /**
     * @var
     * @Slender\Inject
     */
    protected $dummyProtectedProperty;

    /**
     * @var
     * @Slender\Inject("my-custom-service")
     */
    public $customProperty;


    /**
     * @var
     * @Slender\Inject("no-defined-service")
     */
    private $hasNoSetterDefined;


    public function setDummyProtectedProperty($dpp){
        $this->dummyProtectedProperty = $dpp;
    }
}

class ResolverStackTest extends \PHPUnit_Framework_TestCase
{


    public function testConstructorCreatesAnnotationReader()
    {
        $di = new DependencyInjector();

        $refl = new \ReflectionClass($di);
        $p = $refl->getProperty('annotationReader');
        $p->setAccessible(true);

        $this->assertNotEmpty($p->getValue($di));
    }


    public function testSetDiContainerWorks()
    {
        $di = new DependencyInjector();

        $refl = new \ReflectionClass($di);
        $p = $refl->getProperty('container');
        $p->setAccessible(true);

        $di->setDiContainer('FOO');

        $this->assertNotEmpty($p->getValue($di));
        $this->assertEquals('FOO',$p->getValue($di));

    }


    public function testGetDiRequirements()
    {
        $di = new DependencyInjector();

        $data = $di->getDiRequirements('Slender\Core\DependencyInjector\DummyClassForTesting');

        // Test is an array
        $this->assertInternalType('array',$data);

        // Test canonical binding (no identifier)
        $this->assertArrayHasKey('dummyProtectedProperty',$data);
        $myArray = $data['dummyProtectedProperty'];
        $this->assertInternalType('array',$myArray);
        $this->assertArrayHasKey('identifier',$myArray);
        $this->assertArrayHasKey('useSetter',$myArray);
        $this->assertEquals($myArray['identifier'],'dummy-protected-property');
        $this->assertTrue($myArray['useSetter']);

        // Test explicit binding (with identifier)
        $this->assertArrayHasKey('customProperty',$data);
        $myArray = $data['customProperty'];
        $this->assertInternalType('array',$myArray);
        $this->assertArrayHasKey('identifier',$myArray);
        $this->assertArrayHasKey('useSetter',$myArray);
        $this->assertEquals($myArray['identifier'],'my-custom-service');
        $this->assertFalse($myArray['useSetter']);
    }


    public function testPrepare()
    {
        $obj = new DummyClassForTesting();
        $di = new DependencyInjector();
        $di->setDiContainer(array(
                'dummy-protected-property' => 'FOO',
                'my-custom-service' => 'BAR',
            ));

        // Expect exception thrown because has no setter method
        // for private property
        $this->setExpectedException('RuntimeException');

        $di->prepare($obj);

        // TEst!
        $this->assertAttributeNotEmpty('dummyProtectedProperty',$obj);
        $this->assertAttributeEquals('FOO','dummyProtectedProperty',$obj);

        $this->assertAttributeNotEmpty('customProperty',$obj);
        $this->assertAttributeEquals('BAR','customProperty',$obj);


    }
}
