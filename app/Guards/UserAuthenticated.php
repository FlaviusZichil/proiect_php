<?php

namespace App\Guards;

use Framework\Guard;

class UserAuthenticated implements Guard
{
    public function handle(array $params = null)
    {
        session_start();

        if(!isset($_SESSION['firstName']))
        {
            $this->reject();
        }
    }

    public function reject()
    {
        header("Location: /");
    }
}