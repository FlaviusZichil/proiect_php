<?php

namespace App\Controllers;

use App\Config;
use Framework\Controller;
use PDO;

class LoginController extends Controller
{
    public function loginPageAction() {
        /** @noinspection PhpVoidFunctionResultUsedInspection */
        echo $this->view("Home/loginView.html", []);
    }

    public function login()
    {
        echo "salut";
        echo $_POST["emailLogin"];
        echo $_POST["passwordLogin"];
    }

    function loginUser(string $username, $password)
    {

    }

    public function logout()
    {

    }
}