<?php

namespace App\Models;

use Framework\Model;

class Medal extends Model
{
    protected $table="medal";

    public function addMedal($userId, $medalId){
        $this->addMedalForUser($userId, $medalId);
    }

    public function getMedalIdByLocation($location){
        return $this->getFieldBy("medal_id", "location", $location);
    }

    public function hasAlreadyThisMedal($userId, $medalId){
        if($this->checkUserForMedal($userId, $medalId)){
            return true;
        }
        return false;
    }
}