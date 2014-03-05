<?php

namespace Slender\Module\Controllers;

/**
 * Interface ControllerInterface
 *
 * Provides a consistent interface for dispatching actions on a controller
 *
 * @package Slender\Module\Controllers
 */
interface ControllerInterface
{
    /**
     * Dispatches an action on this controller.
     * For example, the base implementation invokes $this->$action($args...)
     *
     * @param  string $action Action to dispatch
     * @param  array  $args   Array of arguments
     * @return mixed
     */
    public function dispatchAction($action, array $args = array());

    public function setDiContainer($diContainer);

}
