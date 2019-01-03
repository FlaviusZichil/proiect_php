<?php /** @noinspection ALL */

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

    function loginUser(string $email, $password){
        $pdo = $this->newDbCon();

        $sql = "SELECT * FROM user WHERE 'email'=?";
        $stmt = $pdo -> prepare($sql);
        $stmt -> execute([$email]);

        if ($row = $stmt->fetch()) {
            if($row && password_verify($password, $row['password'])) {
                session_start();
                $_SESSION["firstName"] = $row["firstName"];
                $_SESSION["secondName"] = $row["secondName"];
                $_SESSION["email"] = $row["email"];
                header("location: /user/");
            }
        }else{
            header("location: /login/");
        }
    }

    public function getUserByEmail(string $email){
        return $this->getByEmail($email);
    }
}