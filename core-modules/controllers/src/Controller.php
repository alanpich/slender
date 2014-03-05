<?php
namespace Slender\Module\Controllers;

/**
 * Class Controller
 *
 * Provides a base controller setup for executing methods as actions.
 *
 * In this case, an action is simply a method on the controller. When the
 * action is dispatched, $this->beforeAction() is called, passing it any arguments
 * meant for the action. If beforeAction() returns anything but NULL, then
 * execution will halt.
 *
 * @TODO    Do something useful with the return result? Or just let it crash and burn?
 *
 * If NULL is returned from beforeAction(), the method $action will be called.
 * This is where you would process any input and render a response back to the
 * user.
 *
 * After the action has been executed, $this->afterAction() is called. If $action()
 * returned anything, it is passed to afterAction() along with the original
 * action arguments.
 *
 * The return value of afterAction() is then used as the return value of the
 * original dispatch() call
 *
 *
 * @package Slender\Module\Controllers
 */
abstract class Controller implements ControllerInterface
{

    protected $diContainer;

    /**
     * Dispatch an action on this controller
     *
     * I'm sure it's spelt 'dEspatch', but oh well...
     *
     * @param  string     $action Name of action to be executed
     * @param  array      $args   Arguments to pass to action
     * @return mixed|void
     */
    public function dispatchAction($action, array $args = array())
    {
        /**
         * Run the pre-flight checks, and halt if
         * it returns anything but NULL
         */
        $haltBefore = $this->beforeAction($args);
        if ($haltBefore !== null) {
            return $haltBefore;
        }

        /**
         * Execute the action and keep the response
         */
        $method = $action."Action";
        $response = $this->executeMethod($method, $args);

        /**
         * Execute post processing on action response and
         * return the result
         */

        return $this->afterAction($response,$args);
    }

    /**
     * Executed before action is called. Returning anything
     * but NULL will cancel the action
     *
     * @param  array      $args Arguments passed to action
     * @return null|mixed Returning anything but NULL will cancel the action
     */
    public function beforeAction(array $args = array())
    {
        if (is_null($args)) { $args = array(); }

        return null;
    }

    /**
     * Executed after an action is called. Is passed
     * the action's response and original arguments.
     *
     * The value returned from this method should be
     * the final result of the dispatch
     *
     * @param  mixed $response Return value from Action
     * @param  array $args     Original arguments passed to action
     * @return mixed
     */
    public function afterAction($response, array $args = array())
    {
        if (is_null($args)) { $args = array(); }

        return $response;
    }

    /**
     * Executes a method on $this passing it array of $args
     *
     * Is just sugar for call_user_func_array($this,$method,$args);
     *
     * @param string $method Method to call
     * @param array  $args   Arguments to pass to method
     */
    protected function executeMethod($method, $args)
    {
        call_user_func_array(array($this, $method), $args);
    }

    public function setDiContainer($diContainer)
    {
        $this->diContainer = $diContainer;
    }

    public function get($key)
    {
        return $this->diContainer[$key];
    }
}
