<?php
namespace Skeleton;

use Slender\Core\DependencyInjector\Annotation as Slender;
use Slender\Module\RouteManager\Controller\AbstractController;


/**
 * Class HomepageController
 *
 * @package Skeleton
 *
 */
class HomepageController extends AbstractController
{

    public function index()
    {
        $this->render('home',array(
                'title' => 'This is a dynamic title'
            ));
    }

}
