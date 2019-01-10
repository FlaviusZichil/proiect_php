<?php

namespace App\Models;

use Framework\Model;

class Medal extends Model
{
    protected $table="medal";

    public function addMedal($userId, $medalId){
        $userMedals = new UserMedals();
        $userMedals->addMedalForUser($userId, $medalId);
    }

    public function getMedalIdByLocation($location){
        return $this->getFieldBy("medal_id", "location", $location);
    }

    public function hasAlreadyThisMedal($userId, $medalId){
        $userMedals = new UserMedals();
        if($userMedals->checkUserForMedal($userId, $medalId)){
            return true;
        }
        return false;
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

    public function getAllFromMedal(): array{
        return $this->getAll();
    }
}