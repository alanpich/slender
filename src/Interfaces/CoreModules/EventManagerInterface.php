<?php
namespace Slender\Interfaces\CoreModules;

/**
 * Interface EventManagerInterface
 *
 * Describes the required interface for
 * an event-manager compatible module.
 *
 * If you are overwriting the core event-manager,
 * it MUST implement this interface
 *
 * @package Slender\Interfaces
 */
interface EventManagerInterface
{
    /**
     * Hook an event
     *
     * @param string   $event    Event/Hook name
     * @param callable $callback Event handler
     * @param int      $priority 0 = high, 10 = low
     */
    public function hook($event, callable $callback, $priority = 10);

    /**
     * Alias for self::hook()
     *
     * @param          $event
     * @param callable $callback
     * @param int      $priority
     */
    public function addEventListener($event, callable $callback, $priority = 10);

    /**
     * Trigger a hook
     *
     * @param string $name    the hook name
     * @param mixed  $hookArg (Optional) Argument for hooked functions
     */
    public function applyHook($name, $hookArg = null);


    /**
     * Alias for self::applyHook()
     *
     * @param string     $event Event name
     * @param array|null $args  Arguments to pass to callbacks
     */
    public function trigger($event, array $args = array());

    /**
     * Trigger a chained hook
     *  - The first callback to return a non-null value
     *    will be returned
     *
     * @param string $name    the hook name
     * @param mixed  $hookArg (Optional) Argument for hooked functions
     * @return mixed|void
     */
    public function applyChain($name, $hookArg = null);

    /**
     * Alis for self::applyChain()
     *
     * @param string $event Event name
     * @param array $args Array of arguments to pass to callbacks
     * @return mixed|void
     */
    public function triggerChain($event, array $args = array());



} 