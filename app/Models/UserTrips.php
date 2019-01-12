<?php

namespace App\Models;

use Framework\Model;

class UserTrips extends Model
{
    protected $table = "user_trips";

    public function getUserIdsFromUserTrips($trip_id){
        $db = $this->newDbCon();
        $stmt = $db->prepare("SELECT user_id FROM $this->table WHERE trip_id=?");
        $stmt->execute([$trip_id]);
        return $stmt->fetchAll();
    }

    public function getDataFromUserTrips($user_id, $trip_id){
        $db = $this->newDbCon();
        $stmt = $db->prepare("SELECT user_id, trip_id FROM $this->table WHERE user_id=? AND trip_id=?");
        $stmt->execute([$user_id, $trip_id]);
        return $stmt->fetch();
    }

    public function addTripForUser($userId, string $tripId){
        $db = $this->newDbCon();
        $stmt = $db->prepare("INSERT INTO $this->table (user_id, trip_id) VALUES(?, ?)");
        $stmt->execute([$userId, $tripId]);
    }
}