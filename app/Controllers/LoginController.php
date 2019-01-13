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
        session_start();

        if(isset($_SESSION["failLoginMessage"])){
            $failLoginMessage = $_SESSION["failLoginMessage"];
            unset($_SESSION["failLoginMessage"]);
        }
        /** @noinspection PhpVoidFunctionResultUsedInspection */
        echo $this->view("Home/loginView.html", ["failLoginMessage" => $failLoginMessage]);
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
            if($user->loginUser($email, $password)){
                session_start();
                $currentUser = $user->getRowByField("email", $email);

                $_SESSION["user_id"] = $currentUser->user_id;
                $_SESSION["firstName"] = $currentUser->first_name;
                $_SESSION["secondName"] = $currentUser->second_name;
                $_SESSION["email"] = $_POST["emailLogin"];

                header("Location: /user/");
            }
            else{
                session_start();
                $_SESSION["failLoginMessage"] = "Date incorecte";
                header("Location: /login/");
            }
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