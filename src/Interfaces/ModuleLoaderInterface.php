<?php
namespace Slender\Interfaces;

interface ModuleLoaderInterface
{

    public function setResolver(ModuleResolverInterface $resolver);

    public function setConfig(\Slim\Configuration $conf);

    public function loadModule($module);

} 