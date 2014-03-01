<?php
namespace Skeleton;

use Slender\App;

class HomepageController
{

    public function index(App $app)
    {
        $app->render('layout');
    }

} 