<?php
namespace App\Controllers;

use App\Models\Guide;
use App\Models\Medal;
use App\Models\Trip;
use App\Models\User;
use App\Models\UserTrips;
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

    // de refactorizat
    public function adminAllTripsPageAction(){
        $trip = new Trip();
        $user = new User();
        $medal = new Medal();
        $userTrips = new UserTrips();

        // if delete trip pressed
        if(isset($_POST["deleteTripId"])){
            $trip->deleteTripById($_POST["deleteTripId"]);
        }

        // gets all trips to show in view
        $allTrips = $trip->getAllTrips();

        // if set finished pressed
        if(isset($_POST["setFinalizedTripId"])){
            $trip->setTripFinalized($_POST["setFinalizedTripId"], "Finalizata");
            $allTrips = $trip->getAllTrips();
            $userIdsAsStdObject = $userTrips->getUserIdsFromUserTrips($_POST["setFinalizedTripId"]);
            $userIdsAsArray = json_decode(json_encode($userIdsAsStdObject), true);

            $selectedTripAsStdObject = $trip->getTripById($_POST["setFinalizedTripId"]);
            $selectedTripAsArray = json_decode(json_encode($selectedTripAsStdObject), true);

            $medalIdAsStdObject = $medal->getMedalIdByLocation($selectedTripAsArray[0]["location"]);
            $medalIdAsArray = json_decode(json_encode($medalIdAsStdObject), true);

            for($i = 0; $i < sizeof($userIdsAsArray); $i++){
                if(!$medal->hasAlreadyThisMedal($userIdsAsArray[$i]["user_id"], $medalIdAsArray["medal_id"])){
                    $medal->addMedal($userIdsAsArray[$i]["user_id"], $medalIdAsArray["medal_id"]);
                }
            }
        }

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
                case "statusAscending":{
                    $allTrips = $trip->getAllTripsOrderBY("ASC", "status");
                    break;
                }
                case "statusDescending":{
                    $allTrips = $trip->getAllTripsOrderBY("DESC", "status");
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