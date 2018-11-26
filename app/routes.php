<?php

    $routes = [
        '/user/add' => ['controller' => 'UserController',
                             'action' => 'addUserAction'],
        '/user/{id}' => ['controller' => 'UserController',
                         'action' => 'getUserByIdAction'],
        '/user/delete/{id}' => ['controller' => 'UserController',
                                'action' => 'deleteUserAction'],
    ];
