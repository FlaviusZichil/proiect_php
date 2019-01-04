<?php

namespace App\Models;

use Framework\Model;

class Trip extends Model
{
    protected $table = "trip";

    public function addTripForUser(int $userId, string $tripId){
        $db = $this->newDbCon();
        $stmt = $db->prepare("INSERT INTO user_trips(user_id, trip_id) VALUES(?, ?)");
        $stmt->execute([$userId, $tripId]);
    }

    public function decreaseNumberOfParticipansForTrip($trip_id){
//        $db = $this->newDbCon();
//        $stmt = $db->prepare("UPDATE trip SET locuri_disponibile=? WHERE trip_id=?");
//        $stmt->execute([$userId, $tripId]);
    }

    public function getAllTrips(){
        $allTrips = $this->getAll();
        return $allTrips;
    }
}