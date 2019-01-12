<?php

namespace App\Models;

use Framework\Model;

class Trip extends Model
{
    protected $table = "trip";

    public function modifyNumberOfParticipantsForTrip($tripId, $numberOfParticipants){
        $db = $this->newDbCon();

        if($numberOfParticipants != 1) {
            $stmt = $db->prepare("UPDATE $this->table SET locuri_disponibile=? WHERE trip_id=?");
            $stmt->execute([$numberOfParticipants, $tripId]);
        }
    }

    public function setTripFinalized($tripId, $status){
        $db = $this->newDbCon();
        $stmt = $db->prepare("UPDATE $this->table SET status=? WHERE trip_id=?");
        $stmt->execute([$status, $tripId]);
    }

    public function addNewTrip($location, $altitude, $startDate, $endDate, $locuri){
        $db = $this->newDbCon();
        $stmt = $db->prepare("INSERT INTO $this->table(location, altitude, start_date, end_date, locuri_disponibile) VALUES(?, ?, ?, ?, ?)");
        $stmt->execute([$location, $altitude, $startDate, $endDate, $locuri]);
    }

    public function getAllTripsForUser(string $email){
        $db = $this->newDbCon();
        $stmt = $db->prepare("SELECT trip.trip_id, location, altitude, start_date, end_date FROM user
                                       INNER JOIN user_trips ON user.user_id = user_trips.user_id
                                       INNER JOIN trip ON user_trips.trip_id = trip.trip_id
                                       WHERE user.email=?");
        $stmt->execute([$email]);
        return $stmt->fetchAll();
    }
}