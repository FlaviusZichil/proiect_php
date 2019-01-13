<?php

namespace App\Controllers;

use App\Models\User;
use App\Validators\Validator;
use Framework\Controller;

class RegisterController extends Controller
{
    // /register/
    public function registerPageAction():void {
        session_start();

        if(isset($_SESSION["failRegisterMessage"])){
            $failRegisterMessage = $_SESSION["failRegisterMessage"];
            unset($_SESSION["failRegisterMessage"]);
        }
        /** @noinspection PhpVoidFunctionResultUsedInspection */
        echo $this->view("Home/registerView.html", ["failRegisterMessage" => $failRegisterMessage]);
    }

    // /register/auth/
    public function register():void {
        $validator = new Validator();
        // gets data from form
        $firstName = $_POST["registerFirstName"];
        $secondName = $_POST["registerSecondName"];
        $email = $_POST["registerEmail"];
        $password = $_POST["registerPassword"];

        if($this->isValidFormData($firstName, $secondName, $email, $password) && !$validator->isEmailTaken($email)){
            $user = new User();
            // registers the user
            $user->registerUser($firstName, $secondName, $email, $password);
            // gets current user id
            $addedUserID = $user->getFieldBy("user_id", "email", $email);
            // starts the session and adds user data to it
            session_start();
            $_SESSION["firstName"] = $firstName;
            $_SESSION["secondName"] = $secondName;
            $_SESSION["email"] = $email;
            $_SESSION["user_id"] = $addedUserID->user_id;

            header("Location: /user/");
        }else{
            session_start();
            $_SESSION["failRegisterMessage"] = "Date incorecte";
            header("Location: /register/");
        }
    }

    private function isValidFormData(string $firstName, $secondName, $email, $password):bool {
        $validator = new Validator();
        $formValidator = false;

        if($validator->isNameValid($firstName) && $validator->isNameValid($secondName)){
            if($validator->isEmailValid($email)){
                if($validator->isPasswordValid($password)){
                    $formValidator = true;
                }
            }
        }
        return $formValidator;
    }
}