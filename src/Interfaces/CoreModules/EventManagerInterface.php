<?php
/**
 * Slender - Slim, but with a bit more meat
 *
 * @author      Alan Pich <alan.pich@gmail.com>
 * @copyright   2014 Alan Pich
 * @link        http://alanpich.github.io/slender
 * @license     https://github.com/alanpich/slender/blob/develop/LICENSE
 * @version     0.0.0
 * @package     Slender
 *
 * MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
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
     * @param  string     $name    the hook name
     * @param  mixed      $hookArg (Optional) Argument for hooked functions
     * @return mixed|void
     */
    public function applyChain($name, $hookArg = null);

    /**
     * Alis for self::applyChain()
     *
     * @param  string     $event Event name
     * @param  array      $args  Array of arguments to pass to callbacks
     * @return mixed|void
     */
    public function triggerChain($event, array $args = array());

}
