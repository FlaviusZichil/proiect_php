<?php

namespace App\Models;

use Framework\Model;

class Medal extends Model
{
    public function addMedal($userId, $medalId){
        $this->addMedalForUser($userId, $medalId);
    }

    public function hasAlreadyThisMedal($userId, $medalId){
        if($this->checkUserForMedal($userId, $medalId)){
            return true;
        }
        return false;
    }
}