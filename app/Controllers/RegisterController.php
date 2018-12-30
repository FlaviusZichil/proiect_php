<?php

namespace App\Controllers;

use App\Models\User;
use Framework\Controller;

class RegisterController extends Controller
{
    // /register/
    public function registerPageAction()
    {
        /** @noinspection PhpVoidFunctionResultUsedInspection */
        echo $this->view("Home/registerView.html", []);
    }

    // /register/auth/
    public function register()
    {
        $user = new User();
//        $user->registerUser("Fla22", "bbbbbb", "cccccc8989", "ddd");
//        $user->registerUser($_POST['registerFirstName'], $_POST['registerSecondName'], $_POST['registerEmail'], $_POST['registerPassword']);

        echo $_POST['registerFirstName'];

        header("Location: /");
    }
}