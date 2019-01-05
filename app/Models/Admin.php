<?php

namespace App\Models;

class Admin
{
    function loginAdmin(){
        session_start();
        $_SESSION["firstName"] = "admin";
        $_SESSION["secondName"] = "admin";
        $_SESSION["email"] = "FlaviusZichil@admin.com";
        header("Location: /admin/");
    }
}