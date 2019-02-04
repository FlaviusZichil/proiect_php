<?php
namespace App\Controllers;

use App\Models\Guide;
use App\Models\Medal;
use App\Models\Trip;
use App\Models\User;
use App\Models\UserMedals;
use App\Models\UserTrips;
use App\Validators\Validator;
use Framework\Controller;

class AdminController extends Controller
{
    public function adminPageAction(){
        /** @noinspection PhpVoidFunctionResultUsedInspection */
        echo $this->view("Admin/adminView.html");
    }

    public function adminAllUsersPageAction(){
        $user = new User();
        // tests if any delete user button is pressed
        if(isset($_POST["deleteUserId"])){
            // removes the user from DB
            $user->deleteById("user_id", $_POST["deleteUserId"]);
        }
        // gets all users from DB
        $allUsers = $user->getAll();
        // tests if sort button is pressed
        if(isset($_POST["userSortButton"])){
            $allUsers = $this->sortUsers($_POST["userSortButton"]);
        }
        /** @noinspection PhpVoidFunctionResultUsedInspection */
        echo $this->view("Admin/allUsers.html", ["allUsers" => $allUsers]);
    }

    public function adminAllTripsPageAction(){
        $trip = new Trip();
        // tests if delete trip button pressed
        if(isset($_POST["deleteTripId"])){
            // delete selected trip
            $trip->deleteById("trip_id", $_POST["deleteTripId"]);
        }
        // tests if set finished button pressed
        if(isset($_POST["setFinalizedTripId"])){
            // sets trip status to finished
            $trip->update(["trip_id" => $_POST["setFinalizedTripId"]], ["status" => "Finalizata"]);
            // adds medals to all users that participated to the trip that was set to finished
            $this->addMedalForUser($_POST["setFinalizedTripId"]);
        }
        // gets all trips to show in view
        $allTrips = $trip->getAll();
        // tests is sort button is pressed
        if(isset($_POST["tripSortButton"])){
            $allTrips = $this->sortTrips($_POST["tripSortButton"]);
        }
        /** @noinspection PhpVoidFunctionResultUsedInspection */
        echo $this->view("Admin/allTrips.html", ["allTrips" => $allTrips]);
    }

    private function addMedalForUser($tripId){
        $medal = new Medal();
        $trip = new Trip();
        $userTrips = new UserTrips();
        $userMedals = new UserMedals();
        // get all user's ids that participated to selected trip
        $allUserIdsForThisTrip = $userTrips->getUserIdsFromUserTrips($tripId);
        // gets all about selected trip
        $selectedTrip = $trip->findOne(["trip_id" => $tripId]);
        // gets medal id for medal that has the same location as selected trip
        $medalId = $medal->getFieldBy("medal_id", "location", $selectedTrip->location);

        foreach ($allUserIdsForThisTrip as $userId){
            // tests if the user already have that medal
            if($medalId != null) {
                if(!$medal->hasAlreadyThisMedal($userId->user_id, $medalId->medal_id)){
                    // adds the medal to the user
                    $userMedals->new(["user_id" => $userId->user_id, "medal_id" => $medalId->medal_id]);
                }
            }
        }
    }

    private function sortTrips($sortButton){
        $trip = new Trip();
        switch ($sortButton){
            case "locationAscending": {
                return $allTrips = $trip->getAllOrderBY("ASC", "location");
            }
            case "locationDescending":{
                return $allTrips = $trip->getAllOrderBY("DESC", "location");
            }
            case "statusAscending":{
                return $allTrips = $trip->getAllOrderBY("ASC", "status");
            }
            case "statusDescending":{
                return $allTrips = $trip->getAllOrderBY("DESC", "status");
            }
            default:{
                return "thereIsNoSuchButton";
            }
        }
    }

    private function sortUsers($sortButton){
        $user = new User();
        switch ($sortButton){
            case "numeCrescator": {
                return $allUsers = $user->getAllOrderBY("ASC", "second_name");
            }
            case "numeDescrescator":{
                return $allUsers = $user->getAllOrderBY("DESC", "second_name");
            }
            case "prenumeCrescator":{
                return $allUsers = $user->getAllOrderBY("ASC", "first_name");
            }
            case "prenumeDescrescator":{
                return $allUsers = $user->getAllOrderBY("DESC", "first_name");
            }
            default:{
                return "thereIsNoSuchButton";
            }
        }
    }

    public function adminAddTrip(){
        $validator = new Validator();
        // gets data about new trip from form
        $newLocation = $_POST["tripLocation"];
        $newAltitude = $_POST["tripAltitude"];
        $newStartDate = $_POST["tripStartDate"];
        $newEndDate = $_POST["tripEndDate"];
        $newAvailableRegistrations = $_POST["tripAvailableRegistrations"];
        // tests if data is valid
        if($validator->isNameValid($newLocation) && $newAltitude && $newStartDate && $newEndDate && $newAvailableRegistrations){
            $trip = new Trip();
            // adds new trip to DB
            $trip->new(["location" => $newLocation, "altitude" => $newAltitude, "start_date" => $newStartDate, "end_date" => $newEndDate, "locuri_disponibile" => $newAvailableRegistrations]);
            $successMessage = "Calatorie adaugata cu succes";
        }
        elseif(!$newLocation || !$newAltitude || !$newStartDate || !$newEndDate){
            // shows no message if no data is entered
        }
        else{
            $failMessage = "Campul locatie este completat incorect";
        }
        /** @noinspection PhpVoidFunctionResultUsedInspection */
        echo $this->view("Admin/addTrip.html", ["successMessage" => $successMessage, "failMessage" => $failMessage]);
    }

    public function adminAllGuides(){
        $guide = new Guide();
        // tests if any delete guide button is pressed
        if(isset($_POST["deleteGuideId"])){
            // deletes the guide from DB
            $guide->deleteById("guide_id", $_POST["deleteGuideId"]);
        }
        // gets all guide from DB
        $allGuides = $guide->getAll();
        /** @noinspection PhpVoidFunctionResultUsedInspection */
        echo $this->view("Admin/allGuides.html", ["allGuides" => $allGuides]);
    }

    public function adminAddGuide(){
        $validator = new Validator();
        // gets new guide data from form
        $newGuideFirstName = $_POST["guideFirstName"];
        $newGuideSecondName = $_POST["guideSecondName"];
        $newGuideCity = $_POST["guideCity"];
        $newGuideExperience = $_POST["guideExperience"];
        // tests is data is valid
        if($newGuideExperience
            && $validator->isNameValid($newGuideFirstName)
            && $validator->isNameValid($newGuideSecondName)
            && $validator->isNameValid($newGuideCity)){
            $guide = new Guide();
            // adds new guide to DB
            $guide->new(["first_name" => $newGuideFirstName, "second_name" => $newGuideSecondName, "years_of_experience" => $newGuideExperience, "city" => $newGuideCity]);

            $successMessage = "Ghid adaugat cu success";
        }
        elseif(!$newGuideFirstName || !$newGuideSecondName || !$newGuideCity){
            // shows no message if no data is entered
        }
        else{
            $failMessage = "Unul dintre campurile Nume, Prenume sau Oras sunt completate incorect";
        }
        /** @noinspection PhpVoidFunctionResultUsedInspection */
        echo $this->view("Admin/addGuide.html", ["successMessage" => $successMessage, "failMessage" => $failMessage]);
    }
}