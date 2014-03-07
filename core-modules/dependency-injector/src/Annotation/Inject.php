<?php
namespace Slender\Module\DependencyInjector\Annotation;

/**
 * Class Inject
 *
 * @package Slender\Module\DependencyInjector\Annotation
 * @Annotation
 */
class Inject
{
    protected $identifier = null;

    public function __construct(array $values)
    {
        $this->identifier = $values['value'];
    }

    /**
     * @param mixed $identifier
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * @return mixed
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }


} 
