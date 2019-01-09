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
    // good
    function loginUser(string $email, $password){
        $user = $this->getAllByField("email", $email);
        $userAsArray = json_decode(json_encode($user), True);

        if ($user != null && password_verify($password, $userAsArray["password"])){
            return true;
        }
        return false;
    }

    public function getUserIdsFromUserTrips($trip_id){
        $db = $this->newDbCon();
        $stmt = $db->prepare("SELECT user_id FROM user_trips WHERE trip_id=?");
        $stmt->execute([$trip_id]);

        $ids = array();

        while(($row =  $stmt->fetch())) {
            array_push($ids, $row);
        }
        return $ids;
    }

    public function getDataFromUserTrips($user_id, $trip_id){
        $db = $this->newDbCon();
        $stmt = $db->prepare("SELECT user_id, trip_id FROM user_trips WHERE user_id=? AND trip_id=?");
        $stmt->execute([$user_id, $trip_id]);

        return $stmt->fetch();
    }

    public function getAllMedalsForUser(string $email){
        $db = $this->newDbCon();
        $stmt = $db->prepare("SELECT medal.medal_id, location, altitude FROM user
                                       INNER JOIN user_medals ON user.user_id = user_medals.user_id
                                       INNER JOIN medal ON user_medals.medal_id = medal.medal_id
                                       WHERE user.email=?");
        $stmt->execute([$email]);

        $medals = array();

        while(($row =  $stmt->fetch())) {
            array_push($medals, $row);
        }

        return $medals;
    }

    public function getAllTripsForUser(string $email){
        $db = $this->newDbCon();
        $stmt = $db->prepare("SELECT trip.trip_id, location, altitude, start_date, end_date FROM user
                                       INNER JOIN user_trips ON user.user_id = user_trips.user_id
                                       INNER JOIN trip ON user_trips.trip_id = trip.trip_id
                                       WHERE user.email=?");
        $stmt->execute([$email]);
        $trips = array();

        while(($row =  $stmt->fetch())) {
            array_push($trips, $row);
        }

        return $trips;
    }
    // good
    public function getUserIdByEmail($email){
        return $this->getFieldBy("user_id", "email", $email);
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

    public function deleteTripForUser($tripId){
        $db = $this->newDbCon();
        $stmt = $db->prepare("DELETE FROM user_trips WHERE trip_id=?");
        $stmt->execute([$tripId]);
    }
    // good
    public function deleteUserById($userId){
        $this->deleteById($userId, "user_id");
    }
    // good
    public function updateUserInDB(string $firstName, $secondName, $password){
        $this->updateUser($firstName, $secondName, $password);
    }
    // good
    public function getUserByEmail(string $email){
        return $this->getFieldBy("email", "email", $email);
    }
    // good
    public function getAllAboutUserByEmail(string $email){
       return $this->getAllByField("email", $email);
    }
    // good
    public function getAllUsers(){
        return $this->getAll();
    }
}