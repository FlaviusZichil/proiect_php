<?php

namespace App\Models;

use Framework\Model;

class Trip extends Model
{
    protected $table = "trip";

    public function getAllTrips(){
        $allTrips = $this->getAll();

        return $allTrips;
    }
}