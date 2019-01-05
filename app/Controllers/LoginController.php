<?php

namespace App\Controllers;

use App\Config;
use App\Models\Admin;
use App\Models\User;
use Framework\Controller;
use PDO;

class LoginController extends Controller
{
    // /login/
    public function loginPageAction() {
        /** @noinspection PhpVoidFunctionResultUsedInspection */
        echo $this->view("Home/loginView.html", []);
    }

    // /login/auth/
    function login(){
        $email = $_POST["emailLogin"];
        $password = $_POST["passwordLogin"];

        if($email == "FlaviusZichil@admin.com" && $password == "houses22"){
            $admin = new Admin();
            $admin->loginAdmin();
        }
        else{
            $user = new User();
            $user->loginUser($email, $password);
        }
    }

    public function logout(){
        session_start();

        if(isset($_COOKIE[session_name()])){
            setcookie(session_name(), "", time()-3600, "/");
        }
        $_SESSION = array();
        session_destroy();

        header("Location: /");
    }
}