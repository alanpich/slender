<?php

namespace Slender\Core\DependencyInjector\Annotation;


class InjectTest extends \PHPUnit_Framework_TestCase
{

    public function testSetIdentifier()
    {
        $refl = new \ReflectionClass('Slender\Core\DependencyInjector\Annotation\Inject');
        $p = $refl->getProperty('identifier');
        $p->setAccessible(true);

        $obj = new Inject(array());
        $obj->setIdentifier('foo');
        $this->assertEquals($p->getValue($obj),'foo');
    }


    public function testGetIdentifier()
    {
        $obj = new Inject(array());

        $refl = new \ReflectionClass('Slender\Core\DependencyInjector\Annotation\Inject');
        $p = $refl->getProperty('identifier');
        $p->setAccessible(true);
        $p->setValue($obj,'bar');

        $this->assertEquals($obj->getIdentifier(),'bar');
    }

}
