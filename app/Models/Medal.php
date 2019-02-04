<?php

namespace App\Models;

use Framework\Model;

class Medal extends Model
{
    protected $table="medal";

    public function hasAlreadyThisMedal($userId, $medalId){
        $userMedals = new UserMedals();
        if($userMedals->findOne(["user_id" => $userId, "medal_id" => $medalId])){
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
        return $stmt->fetchAll();
    }
}