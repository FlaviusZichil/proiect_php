<?php

namespace App\Controllers;

use App\Models\User;
use Framework\Controller;

class RegisterController extends Controller
{
    // /register/
    public function registerPageAction():void {
        /** @noinspection PhpVoidFunctionResultUsedInspection */
        echo $this->view("Home/registerView.html", []);
    }

    // /register/auth/
    public function register():void {
        // gets data from form
        $firstName = $_POST["registerFirstName"];
        $secondName = $_POST["registerSecondName"];
        $email = $_POST["registerEmail"];
        $password = $_POST["registerPassword"];

        if($this->isValidFormData($firstName, $secondName, $email, $password) && !$this->isEmailTaken($email)){
            $user = new User();
            // registers the user
            $user->registerUser($firstName, $secondName, $email, $password);

            // gets current user id
            $addedUserID = $user->getFieldBy("user_id", "email", $email);
            $addedUserAsArray = json_decode(json_encode($addedUserID), true);

            // starts the session and adds user data to it
            session_start();
            $_SESSION["firstName"] = $firstName;
            $_SESSION["secondName"] = $secondName;
            $_SESSION["email"] = $email;
            $_SESSION["user_id"] = $addedUserAsArray["user_id"];

            header("Location: /user/");
        }else{
            header("Location: /register/");
        }
    }

    private function isEmailTaken(string $email):bool {
        $user = new User();
        $emailTakenValidator = false;

        if($user->getFieldBy("email", "email", $email) != null){
            $emailTakenValidator = true;
        }

        return $emailTakenValidator;
    }

    public function isNameValid($username): bool{
        $usernameValidator = true;

        if(!isset($username) || strlen($username) < 2) {
            $usernameValidator = false;
        }
        return $usernameValidator;
    }

    private function isEmailValid($email): bool{
        $emailValidator = true;

        if(!isset($email) || strlen($email) < 10 || strpos('@', $email)) {
            $emailValidator = false;
        }
        return $emailValidator;
    }

    public function isPasswordValid($password): bool{
        $passwordValidator = true;

        if(!isset($password) || strlen($password) < 6) {
            $passwordValidator = false;
        }
        return $passwordValidator;
    }

    private function isValidFormData(string $firstName, $secondName, $email, $password):bool {
        $formValidator = false;

        if($this->isNameValid($firstName) && $this->isNameValid($secondName)){
            if($this->isEmailValid($email)){
                if($this->isPasswordValid($password)){
                    $formValidator = true;
                }
            }
        }
        return $formValidator;
    }
}