<?php
namespace Slender\Module\RouteManager\Controller;

use Slender\Interfaces\DependencyInjectableInterface;
use Slender\Core\DependencyInjector\Annotation as Slender;
use Slender\Core\View;
use Slim\Http\Request;
use Slim\Http\Response;


/**
 * Class AbstractController
 *
 * @package Slender\Module\RouteManager\Controller
 */
abstract class AbstractController
{
    /**
     * Inject the view class into the controller automatically
     * Override this property in your child class to change
     * the view used
     *
     * @var View
     * @Slender\Inject
     */
    protected $view;


    /**
     * @var Request
     * @Slender\Inject
     */
    protected $request;

    /**
     * @var Response
     * @Slender\Inject
     */
    protected $response;


    /**
     * Shortcut for $this->getResponse()->setBody($this->view->fetch($tpl,$data))
     *
     * @param string $tpl  Name of template
     * @param array  $data Key=>Value pairs of data for the template
     */
    protected function render($tpl, $data = array())
    {
        $content = $this->view->fetch($tpl, $data);
        $this->getResponse()->setBody($content);
    }


    /**
     * @param \Slender\Core\View $view
     */
    public function setView($view)
    {
        $this->view = $view;
    }

    /**
     * @return \Slender\Core\View
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * @param \Slim\Http\Request $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * @return \Slim\Http\Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param mixed $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }


}
