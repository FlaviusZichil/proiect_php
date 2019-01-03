<?php

    namespace App\Controllers;

    $routes = [

        '/' =>                       ['controller' => 'IndexController', 'action' => 'indexAction'],

        '/login/' =>                 ['controller' => 'LoginController', 'action' => 'loginPageAction'],

        '/login/auth/' =>            ['controller' => 'LoginController', 'action' => 'login'],

        '/register/' =>              ['controller' => 'RegisterController', 'action' => 'registerPageAction'],

        '/register/auth/' =>         ['controller' => 'RegisterController', 'action' => 'register'],

        '/user/' =>                  ['controller' => 'UserController', 'action' => 'userPageAction', 'guard' => "Authenticated"],

        '/user/mytrips/' =>          ['controller' => 'UserController', 'action' => 'userMyTripsPageAction', 'guard' => "Authenticated"],

        '/user/save/' =>          ['controller' => 'UserController', 'action' => 'userSavePersonalData', 'guard' => "Authenticated"],

        '/user/medals/' =>           ['controller' => 'UserController', 'action' => 'userMedalsPageAction', 'guard' => "Authenticated"],

        '/user/personaldata/' =>     ['controller' => 'UserController', 'action' => 'userPersonalDataPageAction', 'guard' => "Authenticated"],

        '/user/logout/' =>           ['controller' => 'UserController', 'action' => 'logout', 'guard' => "Authenticated"],

    ];
