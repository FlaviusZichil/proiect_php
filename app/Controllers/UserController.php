<?php

namespace App\Controllers;

use App\medal;
use App\Models\Trip;
use App\Models\User;
use Couchbase\UserSettings;
use Framework\Controller;

class UserController extends Controller
{
    public function userPageAction(){
        session_start();
        $firstName = $_SESSION["firstName"];
        $secondName = $_SESSION["secondName"];
        $email = $_SESSION["email"];

        $trip = new Trip();
        $allTrips = $trip->getAllTrips();

        /** @noinspection PhpVoidFunctionResultUsedInspection */
        echo $this->view("User/userView.html", ["firstName" => $firstName, "secondName" => $secondName, "email" => $email, "allTrips" => $allTrips]);
    }

    public function userMyTripsPageAction(){

        //lista cu toate tripurile pt user
        /** @noinspection PhpVoidFunctionResultUsedInspection */
        echo $this->view("User/myTrips.html");
    }

    public function userMedalsPageAction(){
        $medal = new Medal();
        $allMedals = $medal->medals;
        
        /** @noinspection PhpVoidFunctionResultUsedInspection */
        echo $this->view("User/medals.html", ["allMedals" => $allMedals]);
    }

    public function userPersonalDataPageAction(){
        $user = new User();
        // gets data from db as stdClass
        $userToDisplay = $user->getAllDataAboutUserByEmail($_SESSION["email"]);
        // converts from stdClass to array
        $userAsArray = json_decode(json_encode($userToDisplay), true);

        /** @noinspection PhpVoidFunctionResultUsedInspection */
        echo $this->view("User/personalData.html", ["first_name" => $userAsArray["first_name"],
                                                            "second_name" => $userAsArray["second_name"],
                                                            "password" => $userAsArray["password"],
                                                            "email" => $userAsArray["email"]]);
    }

    public function userMyTrips(){

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
            $user->updateUserInDB($firstName, $secondName, $pass);
        }else{
            header("Location: /user/personaldata/");
        }

        $this->userPersonalDataPageAction();
    }

    public function logout(){
       $loginController = new LoginController();
       $loginController->logout();
    }
}