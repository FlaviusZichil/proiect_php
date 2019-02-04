<?php

namespace App\Models;

use Framework\Model;

class Trip extends Model
{
    protected $table = "trip";

    public function modifyNumberOfParticipantsForTrip($tripId, $numberOfParticipants){
        if($numberOfParticipants != 1) {
            $this->update(["trip_id" => $tripId], ["locuri_disponibile" => $numberOfParticipants]);
        }
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