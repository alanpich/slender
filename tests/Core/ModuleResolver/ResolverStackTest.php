<?php

use Slender\Core\ModuleResolver\NamespaceResolver;
use Slender\Core\ModuleResolver\ResolverStack;

class ResolverStackTest extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {

    }



    public function testAddResolversViaConstructor()
    {
        $reflection = new \ReflectionClass('Slender\Core\ModuleResolver\ResolverStack');
        $property = $reflection->getProperty('resolvers');
        $property->setAccessible(true);

        $stack = new ResolverStack(
            new NamespaceResolver(),
            new NamespaceResolver()
        );
        $total = count($property->getValue($stack));

        $this->assertAttributeCount(2,'resolvers',$stack);
    }


    public function testAddResolver()
    {
        $reflection = new \ReflectionClass('Slender\Core\ModuleResolver\ResolverStack');
        $property = $reflection->getProperty('resolvers');
        $property->setAccessible(true);

        $stack = new ResolverStack();
        $this->assertAttributeCount(0,'resolvers',$stack);

        $stack->addResolver(new NamespaceResolver());
        $this->assertAttributeCount(1,'resolvers',$stack);

        $allResolvers = $property->getValue($stack);
        $theResolver = array_shift($allResolvers);
        $this->assertInstanceOf('Slender\Core\ModuleResolver\ModuleResolverInterface',$theResolver);
    }


    public function testPrependResolver()
    {
        $reflection = new \ReflectionClass('Slender\Core\ModuleResolver\ResolverStack');
        $property = $reflection->getProperty('resolvers');
        $property->setAccessible(true);

        $originalResolver = new NamespaceResolver;
        $stack = new ResolverStack($originalResolver);

        $prependedResolver = new NamespaceResolver();
        $stack->prependResolver($prependedResolver);

        $this->assertAttributeContains($prependedResolver,'resolvers',$stack,"Stack resolvers contains prepended resolver");

        $allResolvers = $property->getValue($stack);
        $this->assertEquals($prependedResolver, array_shift($allResolvers));

    }

} 