<?php

namespace App\Controllers;

use Framework\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        /** @noinspection PhpVoidFunctionResultUsedInspection */
        echo $this->view("Home/homeView.html", []);
    }
}