<?php

namespace App\Controllers;

use App\Models\Trip;
use App\Models\User;
use App\Models\UserTrips;
use Framework\Controller;
use Framework\Model;

class UserController extends Controller
{
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

        if(isset($_POST["deleteMyTripId"])){
            $userTrips->deleteById("trip_id", $_POST["deleteMyTripId"]);
            $numberOfParticipants = $trip->getFieldBy("locuri_disponibile", "trip_id", $_POST["deleteMyTripId"]);
            $trip->modifyNumberOfParticipantsForTrip($_POST["deleteMyTripId"], $numberOfParticipants->locuri_disponibile + 1);
        }

        if(!$this->isAlreadyRegisteredForThisTrip($_SESSION["user_id"], $_POST['submitButtonId']) && $_POST['submitButtonId'] != null){
            $userTrips->addTripForUser($_SESSION["user_id"], $_POST['submitButtonId']);
            $numberOfParticipants = $trip->getFieldBy("locuri_disponibile", "trip_id", $_POST["submitButtonId"]);
            $trip->modifyNumberOfParticipantsForTrip($_POST["submitButtonId"], $numberOfParticipants->locuri_disponibile - 1);
        }

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
        $allMedals = $medal->getAll();
        $userMedals = $medal->getAllMedalsForUser($_SESSION["email"]);

        /** @noinspection PhpVoidFunctionResultUsedInspection */
        echo $this->view("User/medals.html", ["allMedals" => $allMedals, "userMedals" => $userMedals]);
    }

    // /user/personaldata/
    public function userPersonalDataPageAction(){
        $user = new User();
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

        $firstName = $_POST["firstNameInput"];
        $secondName = $_POST["secondNameInput"];
        $password = $_POST["changePasswordInput"];

        if($validator->isNameValid($firstName) && $validator->isNameValid($secondName) && $validator->isPasswordValid($password)){
            $pass = password_hash($password, PASSWORD_DEFAULT);
            $user->updateUser($firstName, $secondName, $pass);
        }else{
            header("Location: /user/personaldata/");
        }

        $this->userPersonalDataPageAction();
    }

    // /user/logout/
    public function logout(){
       $loginController = new LoginController();
       $loginController->logout();
    }
}