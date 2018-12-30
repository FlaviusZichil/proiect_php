<?php

namespace App\Models;

use Framework\Model;
use PDO;

class User extends Model{
    protected $table = "user";

    public function registerUser(string $first_name, $second_name, $email, $pass){
        $pdo = $this->newDbCon();

        $sql = "INSERT INTO user(first_name, second_name, email, password) VALUES(?, ?, ?, ?)";
        $password = password_hash($pass, PASSWORD_DEFAULT);

        $stmt = $pdo -> prepare($sql);
        $stmt -> execute([$first_name, $second_name, $email, $password]);
    }
}