<?php
/**
 * Created by PhpStorm.
 * User: FlaviusZichil
 * Date: 1/10/2019
 * Time: 5:00 PM
 */

namespace App\Models;

use Framework\Model;

class UserMedals extends Model
{
    protected $table = "user_medals";

    public function addMedalForUser($userId, $medalId){
        $db = $this->newDbCon();
        $stmt = $db->prepare("INSERT INTO user_medals(user_id, medal_id) VALUES(?, ?)");
        $stmt->execute([$userId, $medalId]);
    }

    public function checkUserForMedal($userId, $medalId){
        $db = $this->newDbCon();
        $stmt = $db->prepare("SELECT * FROM user_medals WHERE user_id=? AND medal_id=?");
        $stmt->execute([$userId, $medalId]);
        return $stmt->fetch();
    }
}