<?php
namespace Slender\Module\EventManager;

use Slender\App;
use Slender\Interfaces\CoreModules\EventManagerInterface;

/**
 * Class EventManager
 *
 * Wraps Slims hook mechanism to provide an interface for binding
 * and triggering events within the app lifecycle.
 *
 * It maintains Slim's original interface for interaction:
 *
 * @example
 *
 *      // Hook an event
 *      $app['event-manager']->hook('slim.before.dispatch',function(){
 *          // Handle event
 *      })
 *
 *      // Trigger a hook
 *      $app['event-manager']->applyHook('slim.before.dispatch',$foo);
 *
 *
 * And also adds a common event dispatcher interface:
 *
 * @example
 *
 *      // Listen for events
 *      $app['event-manager']->on('slim.before.dispatch',function(){
 *          // Handle event
 *      });
 *
 *      // Trigger events
 *      $app['event-manager']->trigger('slim.before.dispatch',array(
 *          $foo,
 *          $bar
 *      ));
 *
 * @package Slender\Module\EventManager
 */
class EventManager implements EventManagerInterface
{
    /** @var \Slender\App */
    protected $app;


    /**
     * @param App $app
     */
    function __construct(App $app)
    {
        $this->app = $app;
    }


    /**
     * Hook an event
     *
     * @param string   $event    Event/Hook name
     * @param callable $callback Event handler
     * @param int      $priority 0 = high, 10 = low
     */
    public function hook($event, callable $callback, $priority = 10)
    {
        $this->app->hook($event, $callback, $priority);
    }


    /**
     * Alias for self::hook()
     *
     * @param          $event
     * @param callable $callback
     * @param int      $priority
     */
    public function addEventListener($event, callable $callback, $priority = 10)
    {
        $this->hook($event, $callback, $priority);
    }


    /**
     * Trigger a hook
     *
     * @param string $name    the hook name
     * @param mixed  $hookArg (Optional) Argument for hooked functions
     */
    public function applyHook($name, $hookArg = null)
    {
        $this->app->applyHook($name, $hookArg);
    }

    /**
     * Trigger a chained hook
     *  - The first callback to return a non-null value
     *    will be returned
     *
     * @param string $name    the hook name
     * @param mixed  $hookArg (Optional) Argument for hooked functions
     * @return mixed|void
     */
    public function applyChain($name, $hookArg = null)
    {
        $hooks = $this->app->getHooks();

        if (!isset($hooks[$name])) {
            $hooks[$name] = array(array());
        }
        if (!empty($hooks[$name])) {
            // Sort by priority, low to high, if there's more than one priority
            if (count($hooks[$name]) > 1) {
                ksort($hooks[$name]);
            }
            foreach ($hooks[$name] as $priority) {
                if (!empty($priority)) {
                    foreach ($priority as $callable) {
                        $value = call_user_func($callable, $hookArg);
                        if ($value !== null) {
                            return $value;
                        }
                    }
                }
            }
        }
    }


    /**
     * Alias for self::applyHook()
     *
     * @param string     $event Event name
     * @param array|null $args  Arguments to pass to callbacks
     */
    public function trigger($event, array $args = array())
    {
        $this->applyHook($event, $args);
    }


    /**
     * Alis for self::applyChain()
     *
     * @param string $event Event name
     * @param array $args Array of arguments to pass to callbacks
     * @return mixed|void
     */
    public function triggerChain($event, array $args = array())
    {
        return $this->applyChain($event,$args);
    }

} 