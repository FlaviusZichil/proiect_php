<?php
namespace App\Controllers;

use App\Models\Guide;
use App\Models\Trip;
use App\Models\User;
use Framework\Controller;

class AdminController extends Controller
{
    public function adminPageAction(){
        /** @noinspection PhpVoidFunctionResultUsedInspection */
        echo $this->view("Admin/adminView.html");
    }

    public function adminAllUsersPageAction(){
        $user = new User();
        $user->deleteUserById($_POST["deleteUserId"]);
        $allUsers = $user->getAllUsers();

        if(isset($_POST["submit"])){
            switch ($_POST["submit"]){
                case "numeCrescator": {
                    $allUsers = $user->getAllUsersOrderBY("ASC", "second_name");
                    break;
                }
                case "numeDescrescator":{
                    $allUsers = $user->getAllUsersOrderBY("DESC", "second_name");
                    break;
                }
                case "prenumeCrescator":{
                    $allUsers = $user->getAllUsersOrderBY("ASC", "first_name");
                    break;
                }
                case "prenumeDescrescator":{
                    $allUsers = $user->getAllUsersOrderBY("DESC", "first_name");
                    break;
                }
            }
        }

        /** @noinspection PhpVoidFunctionResultUsedInspection */
        echo $this->view("Admin/allUsers.html", ["allUsers" => $allUsers]);
    }

    public function adminAllTripsPageAction(){
        $trip = new Trip();
        $trip->deleteTripById($_POST["deleteTripId"]);
        $trip->setTripFinalized($_POST["setFinalizedTripId"]);
        $allTrips = $trip->getAllTrips();

        if(isset($_POST["submit"])){
            switch ($_POST["submit"]){
                case "locationAscending": {
                    $allTrips = $trip->getAllTripsOrderBY("ASC", "location");
                    break;
                }
                case "locationDescending":{
                    $allTrips = $trip->getAllTripsOrderBY("DESC", "location");
                    break;
                }
                case "startDateAscending":{
                    $allTrips = $trip->getAllTripsOrderBY("ASC", "start_date");
                    break;
                }
                case "startDateDescending":{
                    $allTrips = $trip->getAllTripsOrderBY("DESC", "start_date");
                    break;
                }
            }
        }

        /** @noinspection PhpVoidFunctionResultUsedInspection */
        echo $this->view("Admin/allTrips.html", ["allTrips" => $allTrips]);
    }

    public function adminAddTrip(){
        $newLocation = $_POST["tripLocation"];
        $newAltitude = $_POST["tripAltitude"];
        $newStartDate = $_POST["tripStartDate"];
        $newEndDate = $_POST["tripEndDate"];
        $newAvailableRegistrations = $_POST["tripAvailableRegistrations"];

        if($newLocation != null && $newAltitude != null && $newStartDate != null && $newEndDate != null && $newAvailableRegistrations != null){
            $trip = new Trip();
            $trip->addNewTrip($newLocation, $newAltitude, $newStartDate, $newEndDate, $newAvailableRegistrations);
        }

        /** @noinspection PhpVoidFunctionResultUsedInspection */
        echo $this->view("Admin/addTrip.html");
    }

    public function adminAllGuides(){
        $guide = new Guide();
        $guide->deleteGuideById($_POST["deleteGuideId"]);
        $allGuides = $guide->getAll();

        /** @noinspection PhpVoidFunctionResultUsedInspection */
        echo $this->view("Admin/allGuides.html", ["allGuides" => $allGuides]);
    }

    public function adminAddGuides(){
        $newGuideFirstName = $_POST["guideFirstName"];
        $newGuideSecondName = $_POST["guideSecondName"];
        $newGuideCity = $_POST["guideCity"];
        $newGuideExperience = $_POST["guideExperience"];

        if($newGuideFirstName != null && $newGuideSecondName != null && $newGuideCity != null && $newGuideExperience != null){
            $guide = new Guide();
            $guide->addNewGuide($newGuideFirstName, $newGuideSecondName, $newGuideExperience, $newGuideCity);
        }

        /** @noinspection PhpVoidFunctionResultUsedInspection */
        echo $this->view("Admin/addGuide.html");
    }
}