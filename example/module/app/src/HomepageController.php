<?php
namespace Skeleton;

use Slender\App;
use Slender\Interfaces\DependencyInjectableInterface;
use Slender\Module\Controllers\Controller;
use Slender\Module\DependencyInjector\Annotation as Slender;
use Slender\Module\EventManager\EventManager;
use Slender\Module\RouteManager\Controller\AbstractController;
use Slender\Module\RouteManager\RouteManager;


/**
 * Class HomepageController
 *
 * @package Skeleton
 *
 */
class HomepageController
{
    /**
     * @var RouteManager
     * @Slender\Inject("route-manager");
     */
    protected $myRouteManager;

    /**
     * @var EventManager
     * @Slender\Inject
     */
    protected $eventManager;


    public function index()
    {
        die('here');
    }





    /**
     * @param \Slender\Module\EventManager\EventManager $eventManager
     */
    public function setEventManager($eventManager)
    {
        $this->eventManager = $eventManager;
    }

    /**
     * @return \Slender\Module\EventManager\EventManager
     */
    public function getEventManager()
    {
        return $this->eventManager;
    }

    /**
     * @param \Slender\Module\RouteManager\RouteManager $myRouteManager
     */
    public function setMyRouteManager($myRouteManager)
    {
        $this->myRouteManager = $myRouteManager;
    }

    /**
     * @return \Slender\Module\RouteManager\RouteManager
     */
    public function getMyRouteManager()
    {
        return $this->myRouteManager;
    }



}
