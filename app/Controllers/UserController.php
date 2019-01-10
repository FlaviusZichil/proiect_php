<?php

namespace App\Controllers;

use App\medal;
use App\Models\Trip;
use App\Models\User;
use App\Models\UserTrips;
use Framework\Controller;
use Framework\Model;

class UserController extends Controller
{
    public function userPageAction(){
        $trip = new Trip();
        $allTrips = $trip->getAllUnfinishedTrips("Activa");

        /** @noinspection PhpVoidFunctionResultUsedInspection */
        echo $this->view("User/userView.html", ["allTrips" => $allTrips]);
    }

    // /user/mytrips/
    public function userMyTripsPageAction(){
        session_start();
        $trip = new Trip();
        $userTrips = new UserTrips();

        if(isset($_POST["deleteMyTripId"])){
            $userTrips->deleteTripForUser($_POST["deleteMyTripId"]);

            $trip->increaseNumberOfParticipantsForTrip($_POST["deleteMyTripId"]);
        }

        if(!$this->isAlreadyRegisteredForThisTrip($_SESSION["user_id"], $_POST['submitButtonId']) && $_POST['submitButtonId'] != null){
            $userTrips->addTripForUser($_SESSION["user_id"], $_POST['submitButtonId']);
            $trip->decreaseNumberOfParticipantsForTrip($_POST['submitButtonId']);
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
        $allMedals = $medal->getAllFromMedal();
        $userMedals = $medal->getAllMedalsForUser($_SESSION["email"]);

        /** @noinspection PhpVoidFunctionResultUsedInspection */
        echo $this->view("User/medals.html", ["allMedals" => $allMedals, "userMedals" => $userMedals]);
    }

    // /user/personaldata/
    public function userPersonalDataPageAction(){
        $user = new User();
        // gets data from db as stdClass
        $userToDisplay = $user->getAllAboutUserByEmail($_SESSION["email"]);
        // converts from stdClass to array
        $userAsArray = json_decode(json_encode($userToDisplay), true);

        /** @noinspection PhpVoidFunctionResultUsedInspection */
        echo $this->view("User/personalData.html", ["first_name" => $userAsArray["first_name"],
                                                            "second_name" => $userAsArray["second_name"],
                                                            "password" => $userAsArray["password"],
                                                            "email" => $userAsArray["email"]]);
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