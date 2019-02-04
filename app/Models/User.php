<?php /** @noinspection ALL */

namespace App\Models;

use Framework\Model;
use PDO;

class User extends Model{
    protected $table = "user";

    function loginUser(string $email, $password){
        $user = $this->findOne(["email" => $email]);

        if ($user != null && password_verify($password, $user->password)){
            return true;
        }
        return false;
    }
}