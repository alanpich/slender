<?php
namespace Slender\Core\ConfigFinder;

class ConfigFinderTest extends \PHPUnit_Framework_TestCase
{

    public function testConstructorSetsProperties()
    {
        $paths = ['foo','bar'];
        $files = ['x','y','z'];

        $finder = new ConfigFinder($paths,$files);

        $refl = new \ReflectionClass($finder);
        $pPaths = $refl->getProperty('paths');
        $pPaths->setAccessible(true);
        $pFiles = $refl->getProperty('files');
        $pFiles->setAccessible(true);

        $this->assertSame($paths,$pPaths->getValue($finder));
        $this->assertSame($files,$pFiles->getValue($finder));
    }

    public function testFindFiles()
    {
        $finder = new ConfigFinder([dirname(__FILE__)],['foo.txt']);

        $files = $finder->findFiles();

        $this->assertInternalType('array',$files);


    }
} 
