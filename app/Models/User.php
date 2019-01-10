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
        $user = $this->getAllByField("email", $email);

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

    public function getAllUsersOrderBY(string $way, $column){
        $db = $this->newDbCon();
        $stmt = $db->prepare("SELECT * FROM $this->table ORDER BY $column $way");
        $stmt->execute();

        $users = array();

        while(($row =  $stmt->fetch())) {
            array_push($users, $row);
        }
        return $users;
    }

    public function deleteUserById($userId){
        $this->deleteById($userId, "user_id");
    }

    public function getUserByEmail(string $email){
        return $this->getFieldBy("email", "email", $email);
    }

    public function getAllAboutUserByEmail(string $email){
       return $this->getAllByField("email", $email);
    }

    public function getAllUsers(){
        return $this->getAll();
    }

    public function getUserIdByEmail($email){
        return $this->getFieldBy("user_id", "email", $email);
    }
}