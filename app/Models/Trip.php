<?php

namespace App\Models;

use Framework\Model;

class Trip extends Model
{
    protected $table = "trip";
    // increase and decrease should be one method
    public function decreaseNumberOfParticipantsForTrip($tripId){
        $db = $this->newDbCon();
        $numberOfParticipants = $this->getFieldBy("locuri_disponibile", "trip_id", $tripId);
        $participants = $numberOfParticipants->locuri_disponibile;

        if($participants != 1) {
            $stmt = $db->prepare("UPDATE $this->table SET locuri_disponibile=? WHERE trip_id=?");
            $stmt->execute([$participants - 1, $tripId]);
        }
    }
    // increase and decrease should be one method
    public function increaseNumberOfParticipantsForTrip($tripId){
        $db = $this->newDbCon();
        $numberOfParticipants = $this->getFieldBy("locuri_disponibile", "trip_id", $tripId);
        $participants = $numberOfParticipants->locuri_disponibile;

        $stmt = $db->prepare("UPDATE $this->table SET locuri_disponibile=? WHERE trip_id=?");
        $stmt->execute([$participants + 1, $tripId]);
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

    public function getAllTripsOrderBY(string $way, $column){
        $db = $this->newDbCon();
        $stmt = $db->prepare("SELECT * FROM $this->table ORDER BY $column $way");
        $stmt->execute();

        $trips = array();

        while(($row =  $stmt->fetch())) {
            array_push($trips, $row);
        }
        return $trips;
    }

    public function getTripById($tripId){
        $db = $this->newDbCon();
        $stmt = $db->prepare("SELECT * FROM $this->table WHERE trip_id=?");
        $stmt->execute([$tripId]);

        return $stmt->fetchAll();
    }

    public function getAllUnfinishedTrips($status){
        $db = $this->newDbCon();
        $stmt = $db->prepare("SELECT * FROM $this->table WHERE status=?");
        $stmt->execute([$status]);
        return $stmt->fetchAll();
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

    public function getAllTrips(){
        return $allTrips = $this->getAll();
    }

    public function deleteTripById($tripId){
        $this->deleteById($tripId, "trip_id");
    }
}