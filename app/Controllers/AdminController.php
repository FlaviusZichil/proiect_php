<?php
namespace App\Controllers;

use App\Models\Guide;
use App\Models\Medal;
use App\Models\Trip;
use App\Models\User;
use App\Models\UserMedals;
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
        $user->deleteById("user_id", $_POST["deleteUserId"]);
        $allUsers = $user->getAll();

        if(isset($_POST["submit"])){
            switch ($_POST["submit"]){
                case "numeCrescator": {
                    $allUsers = $user->getAllOrderBY("ASC", "second_name");
                    break;
                }
                case "numeDescrescator":{
                    $allUsers = $user->getAllOrderBY("DESC", "second_name");
                    break;
                }
                case "prenumeCrescator":{
                    $allUsers = $user->getAllOrderBY("ASC", "first_name");
                    break;
                }
                case "prenumeDescrescator":{
                    $allUsers = $user->getAllOrderBY("DESC", "first_name");
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
        $userMedals = new UserMedals();

        // if delete trip pressed
        if(isset($_POST["deleteTripId"])){
            $trip->deleteById("trip_id", $_POST["deleteTripId"]);
        }

        // gets all trips to show in view
        $allTrips = $trip->getAll();

        // if set finished pressed
        if(isset($_POST["setFinalizedTripId"])){
            $trip->setTripFinalized($_POST["setFinalizedTripId"], "Finalizata");
            $allTrips = $trip->getAll();
            $userIdsAsStdObject = $userTrips->getUserIdsFromUserTrips($_POST["setFinalizedTripId"]);
            $userIdsAsArray = json_decode(json_encode($userIdsAsStdObject), true);

            $selectedTripAsStdObject = $trip->getById("trip_id", $_POST["setFinalizedTripId"]);
            $selectedTripAsArray = json_decode(json_encode($selectedTripAsStdObject), true);

            $medalIdAsStdObject = $medal->getFieldBy("medal_id", "location", $selectedTripAsArray[0]["location"]);
            $medalIdAsArray = json_decode(json_encode($medalIdAsStdObject), true);

            for($i = 0; $i < sizeof($userIdsAsArray); $i++){
                if(!$medal->hasAlreadyThisMedal($userIdsAsArray[$i]["user_id"], $medalIdAsArray["medal_id"])){
//                    $medal->addMedal($userIdsAsArray[$i]["user_id"], $medalIdAsArray["medal_id"]);
                    $userMedals->addMedalForUser($userIdsAsArray[$i]["user_id"], $medalIdAsArray["medal_id"]);
                }
            }
        }

        if(isset($_POST["submit"])){
            switch ($_POST["submit"]){
                case "locationAscending": {
                    $allTrips = $trip->getAllOrderBY("ASC", "location");
                    break;
                }
                case "locationDescending":{
                    $allTrips = $trip->getAllOrderBY("DESC", "location");
                    break;
                }
                case "statusAscending":{
                    $allTrips = $trip->getAllOrderBY("ASC", "status");
                    break;
                }
                case "statusDescending":{
                    $allTrips = $trip->getAllOrderBY("DESC", "status");
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
        $guide->deleteById("guide_id", $_POST["deleteGuideId"]);
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