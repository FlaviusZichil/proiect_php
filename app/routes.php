<?php

    namespace App\Controllers;

    $routes = [

        '/' =>                       ['controller' => 'IndexController', 'action' => 'indexAction'],

        '/login/' =>                 ['controller' => 'LoginController', 'action' => 'loginPageAction'],

        '/login/auth/' =>            ['controller' => 'LoginController', 'action' => 'login'],

        '/register/' =>              ['controller' => 'RegisterController', 'action' => 'registerPageAction'],

        '/register/auth/' =>         ['controller' => 'RegisterController', 'action' => 'register'],

        '/user/' =>                  ['controller' => 'UserController', 'action' => 'userPageAction', 'guard' => "UserAuthenticated"],

        '/user/mytrips/' =>          ['controller' => 'UserController', 'action' => 'userMyTripsPageAction', 'guard' => "UserAuthenticated"],

        '/user/save/' =>             ['controller' => 'UserController', 'action' => 'userSavePersonalData', 'guard' => "UserAuthenticated"],

        '/user/medals/' =>           ['controller' => 'UserController', 'action' => 'userMedalsPageAction', 'guard' => "UserAuthenticated"],

        '/user/personaldata/' =>     ['controller' => 'UserController', 'action' => 'userPersonalDataPageAction', 'guard' => "UserAuthenticated"],

        '/user/logout/' =>           ['controller' => 'UserController', 'action' => 'logout', 'guard' => "UserAuthenticated"],

        '/admin/' =>                 ['controller' => 'AdminController', 'action' => 'adminPageAction', 'guard' => "AdminAutenticated"],

        '/admin/users/' =>           ['controller' => 'AdminController', 'action' => 'adminAllUsersPageAction', 'guard' => "AdminAutenticated"],

        '/admin/trips/' =>           ['controller' => 'AdminController', 'action' => 'adminAllTripsPageAction', 'guard' => "AdminAutenticated"],

        '/admin/trip/add/' =>        ['controller' => 'AdminController', 'action' => 'adminAddTrip', 'guard' => "AdminAutenticated"],

        '/admin/guides/' =>          ['controller' => 'AdminController', 'action' => 'adminAllGuides', 'guard' => "AdminAutenticated"],

        '/admin/guide/add/' =>      ['controller' => 'AdminController', 'action' => 'adminAddGuide', 'guard' => "AdminAutenticated"],

    ];
