<?php

    namespace App\Controllers;

    $routes = [

        '/' =>                       ['controller' => 'IndexController', 'action' => 'indexAction'],

        '/login/' =>                 ['controller' => 'LoginController', 'action' => 'loginPageAction'],

        '/login/auth/' =>            ['controller' => 'LoginController', 'action' => 'login'],

        '/register/' =>              ['controller' => 'RegisterController', 'action' => 'registerPageAction'],

        '/register/auth/' =>         ['controller' => 'RegisterController', 'action' => 'register'],

        '/user/' =>                  ['controller' => 'UserController', 'action' => 'userPageAction', 'guard' => "Authenticated"],

        '/user/{id}' =>              ['controller' => 'UserController', 'action' => 'getUserByIdAction', 'guard' => "Authenticated"],

        '/user/delete/{id}' =>       ['controller' => 'UserController', 'action' => 'deleteUserAction'],

        '/user/show' =>              ['controller' => 'UserController', 'action' => 'showUser'],
    ];
