<?php
namespace Slender\Core\Util;

interface MyInterfaceForTesting {}
class MyClassForTesting implements MyInterfaceForTesting {}
class MyOtherClassForTesting {}



class UtilTest extends \PHPUnit_Framework_TestCase
{
    public function testImplementsInterface()
    {
        $interface = 'Slender\Core\Util\MyInterfaceForTesting';
        $hasInterfaceClass = 'Slender\Core\Util\MyClassForTesting';
        $noInterfaceClass  = 'Slender\Core\Util\MyOtherClassForTesting';
        $util = new Util();

        // Test works with string classnames
        $this->assertTrue($util->implementsInterface($hasInterfaceClass,$interface));
        $this->assertFalse($util->implementsInterface($noInterfaceClass,$interface));

        // Test works with class instances
        $this->assertTrue($util->implementsInterface(new $hasInterfaceClass,$interface));
        $this->assertFalse($util->implementsInterface(new $noInterfaceClass,$interface));
    }


    public function testHyphenCase()
    {
        $expected = 'my-test-string';
        $this->assertEquals($expected, Util::hyphenCase('MyTestString'));
    }

    public function testSetterMethodName()
    {
        $this->assertEquals('setMyThing',Util::setterMethodName('myThing'));
    }



    public function testStringStartsWith()
    {
        $theString = 'helloWorld';
        $goodPrefix = 'hello';
        $badPrefix =  'world';


        $this->assertTrue( Util::stringStartsWith($theString,$goodPrefix));
        $this->assertFalse( Util::stringStartsWith($theString,$badPrefix));
    }


    public function testStringEndsWith()
    {
        $theString = 'helloWorld';
        $goodPostfix = 'World';
        $badPostfix =  'hello';


        $this->assertTrue( Util::stringEndsWith($theString,$goodPostfix));
        $this->assertFalse( Util::stringEndsWith($theString,$badPostfix));
    }
}
