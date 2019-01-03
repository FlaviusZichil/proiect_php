<?php

namespace App\Controllers;

use App\Models\Trip;
use App\Models\User;
use Framework\Controller;

class UserController extends Controller
{
    public function getUserByIdAction($id): void
    {
        echo "This is the user with id ".$id;
    }

    public function addUserAction(): void
    {
        echo "New user added";
    }

    public function deleteUserAction($id): void
    {
        echo "The user with id ".$id." has been deleted";
    }

    public function showUser()
    {
        /** @noinspection PhpVoidFunctionResultUsedInspection */
        echo $this->view("User/ShowUser.html", ["name" => "Flavius"]);
    }

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
}