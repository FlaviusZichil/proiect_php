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

    public function getDataFromUserTrips($user_id, $trip_id){
        $db = $this->newDbCon();
        $stmt = $db->prepare("SELECT user_id, trip_id FROM user_trips WHERE user_id=? AND trip_id=?");
        $stmt->execute([$user_id, $trip_id]);

        return $stmt->fetch();
    }

    public function getAllMedalsForUser(string $email){
        $db = $this->newDbCon();
        $stmt = $db->prepare("SELECT location, altitude FROM user
                                       INNER JOIN user_medals ON user.user_id = user_medals.user_id
                                       INNER JOIN medal ON user_medals.medal_id = medal.medal_id
                                       WHERE user.email=?");
        $stmt->execute([$email]);

        return $stmt->fetch();
    }

    public function getAllTripsForUser(string $email){
        $db = $this->newDbCon();
        $stmt = $db->prepare("SELECT location, altitude, start_date, end_date FROM user
                                       INNER JOIN user_trips ON user.user_id = user_trips.user_id
                                       INNER JOIN trip ON user_trips.trip_id = trip.trip_id
                                       WHERE user.email=?");
        $stmt->execute([$email]);

        return $stmt->fetch();
    }

    public function getUserIdByEmail($email){
        $db = $this->newDbCon();
        $stmt = $db->prepare("SELECT user_id FROM $this->table WHERE email=?");
        $stmt->execute([$email]);

        return $stmt->fetch();
    }

    public function deleteUserById($userId){
        $db = $this->newDbCon();
        $stmt = $db->prepare("DELETE FROM $this->table WHERE user_id=?");
        $stmt->execute([$userId]);
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

    public function updateUserInDB(string $firstName, $secondName, $password){
        $this->updateUser($firstName, $secondName, $password);
    }

    public function getUserByEmail(string $email){
        return $this->getByEmail($email);
    }

    public function getAllDataAboutUserEmail(string $email){
        return $this->getAllDataAboutUserByEmail($email);
    }

    public function getAllUsers(){
        return $this->getAll();
    }
}