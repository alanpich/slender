<?php
namespace Skeleton;

use Slender\App;
use Slender\Module\Controllers\Controller;

class HomepageController extends Controller
{
    public function indexAction($id)
    {
        //@TODO   This needs to be improved somehow...
        die($this->get('view')->render('home',array()));
    }
} 