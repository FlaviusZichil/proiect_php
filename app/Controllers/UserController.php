<?php

namespace App\Controllers;

use App\Models\Trip;
use App\Models\User;
use App\Models\UserTrips;
use Framework\Controller;
use Framework\Model;

class UserController extends Controller
{
    // /user/
    public function userPageAction(){
        $trip = new Trip();
        $allTrips = $trip->getAllByField("status", "Activa");

        /** @noinspection PhpVoidFunctionResultUsedInspection */
        echo $this->view("User/userView.html", ["allTrips" => $allTrips]);
    }

    // /user/mytrips/
    public function userMyTripsPageAction(){
        session_start();
        $trip = new Trip();
        $userTrips = new UserTrips();
        // tests if delete my trip button has been pressed
        if(isset($_POST["deleteMyTripId"])){
            // removes the trip from user_trips
            $userTrips->deleteById("trip_id", $_POST["deleteMyTripId"]);
            // gets number of participants for deleted trip
            $numberOfParticipants = $trip->getFieldBy("locuri_disponibile", "trip_id", $_POST["deleteMyTripId"]);
            // increases number of participants for deleted trip with 1
            $trip->modifyNumberOfParticipantsForTrip($_POST["deleteMyTripId"], $numberOfParticipants->locuri_disponibile + 1);
        }
        // tests if register for trip button has been pressed and if the user is not already registered for selected trip
        if(isset($_POST['registerForTripId']) && !$this->isAlreadyRegisteredForThisTrip($_SESSION["user_id"], $_POST['registerForTripId'])){
            // adds the selected trip to user_trips
            $userTrips->addTripForUser($_SESSION["user_id"], $_POST['registerForTripId']);
            // gets number of participants for selected trip
            $numberOfParticipants = $trip->getFieldBy("locuri_disponibile", "trip_id", $_POST["registerForTripId"]);
            // decreases the number of participants for selected trip with 1
            $trip->modifyNumberOfParticipantsForTrip($_POST["registerForTripId"], $numberOfParticipants->locuri_disponibile - 1);
        }
        // gets all trips for current user
        $allTripsForUser = $trip->getAllTripsForUser($_SESSION["email"]);

        /** @noinspection PhpVoidFunctionResultUsedInspection */
        echo $this->view("User/myTrips.html", ["userTrips" => $allTripsForUser]);
    }

    private function isAlreadyRegisteredForThisTrip($userId, $tripId):bool{
        $userTrips = new UserTrips();
        $isAlreadyRegistered = false;
        $dataFromUserTrips = $userTrips->getDataFromUserTrips($userId, $tripId);

        if($dataFromUserTrips != null){
            $isAlreadyRegistered = true;
        }
        return $isAlreadyRegistered;
    }

    // /user/medals/
    public function userMedalsPageAction(){
        $medal = new \App\Models\Medal();
        // gets all medals from DB
        $allMedals = $medal->getAll();
        // gets all medals from current user from DB
        $userMedals = $medal->getAllMedalsForUser($_SESSION["email"]);

        /** @noinspection PhpVoidFunctionResultUsedInspection */
        echo $this->view("User/medals.html", ["allMedals" => $allMedals, "userMedals" => $userMedals]);
    }

    // /user/personaldata/
    public function userPersonalDataPageAction(){
        $user = new User();
        // gets user by email
        $userToDisplay = $user->getRowByField("email", $_SESSION["email"]);

        /** @noinspection PhpVoidFunctionResultUsedInspection */
        echo $this->view("User/personalData.html", ["first_name" =>  $userToDisplay->first_name,
                                                            "second_name" =>  $userToDisplay->second_name,
                                                            "password" =>  $userToDisplay->password,
                                                            "email" =>  $userToDisplay->email]);
    }

    // /user/save/
    public function userSavePersonalData(){
        $validator = new RegisterController();
        $user = new User();
        // gets data from form
        $firstName = $_POST["firstNameInput"];
        $secondName = $_POST["secondNameInput"];
        $password = $_POST["changePasswordInput"];
        // tests if data from form is valid
        if($validator->isNameValid($firstName) && $validator->isNameValid($secondName) && $validator->isPasswordValid($password)){
            $pass = password_hash($password, PASSWORD_DEFAULT);
            // updates the user with the new data
            $user->updateUser($firstName, $secondName, $pass);
            $this->userPersonalDataPageAction();
        }else{
            header("Location: /user/personaldata/");
        }
    }

    // /user/logout/
    public function logout(){
       $loginController = new LoginController();
       $loginController->logout();
    }
}