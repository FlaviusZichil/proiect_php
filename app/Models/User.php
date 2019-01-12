<?php /** @noinspection ALL */

namespace App\Models;

use Framework\Model;
use PDO;

class User extends Model{
    protected $table = "user";

    public function registerUser(string $first_name, $second_name, $email, $pass){
        $pdo = $this->newDbCon();
        $sql = "INSERT INTO $this->table (first_name, second_name, email, password) VALUES(?, ?, ?, ?)";
        $password = password_hash($pass, PASSWORD_DEFAULT);
        $stmt = $pdo -> prepare($sql);
        $stmt -> execute([$first_name, $second_name, $email, $password]);
    }

    function loginUser(string $email, $password){
        $user = $this->getRowByField("email", $email);

        if ($user != null && password_verify($password, $user->password)){
            return true;
        }
        return false;
    }

    public function updateUser(string $firstName, $secondName, $password){
        $db = $this->newDbCon();
        $email = $_SESSION["email"];
        $stmt = $db->prepare("UPDATE $this->table SET first_name=?, second_name=?, password=? WHERE email=?");
        $stmt->execute([$firstName, $secondName, $password, $email]);
    }

}