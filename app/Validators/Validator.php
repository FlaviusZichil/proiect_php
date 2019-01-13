<?php
/**
 * Created by PhpStorm.
 * User: FlaviusZichil
 * Date: 1/13/2019
 * Time: 5:17 PM
 */

namespace App\Validators;


use App\Models\User;

class Validator
{
    public function isEmailTaken(string $email):bool {
        $user = new User();
        $emailTakenValidator = false;

        if($user->getFieldBy("email", "email", $email) != null){
            $emailTakenValidator = true;
        }
        return $emailTakenValidator;
    }

    public function isNameValid($username): bool{
        $usernameValidator = true;

        if(!isset($username) || strlen($username) < 2 || preg_match('/\\d/', $username)) {
            $usernameValidator = false;
        }
        return $usernameValidator;
    }

    public function isEmailValid($email): bool{
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
}