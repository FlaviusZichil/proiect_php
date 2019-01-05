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

    public function decreaseNumberOfParticipantsForTrip($tripId){
        $db = $this->newDbCon();
        $numberOfParticipants = $this->getNumberOfParticipantsForTrip($tripId);
        $participants = $numberOfParticipants->locuri_disponibile;

        if($participants != 1) {
            $stmt = $db->prepare("UPDATE trip SET locuri_disponibile=? WHERE trip_id=?");
            $stmt->execute([$participants - 1, $tripId]);
        }
    }

    private function getNumberOfParticipantsForTrip($tripId){
        $db = $this->newDbCon();
        $stmt = $db->prepare("SELECT locuri_disponibile FROM trip WHERE trip_id=?");
        $stmt->execute([$tripId]);

        return $stmt->fetch();
    }

    public function setTripFinalized($tripId){
        $db = $this->newDbCon();
        $newStatus = "Finished";
        $stmt = $db->prepare("UPDATE $this->table SET status=? WHERE trip_id=?");
        $stmt->execute([$newStatus, $tripId]);
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

    public function getAllTrips(){
        return $allTrips = $this->getAll();
    }

    public function deleteTripById($tripId){
        $this->deleteById($tripId, "trip_id");
    }
}