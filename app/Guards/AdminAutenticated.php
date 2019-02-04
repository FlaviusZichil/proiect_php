<?php
/**
 * Created by PhpStorm.
 * User: FlaviusZichil
 * Date: 2/3/2019
 * Time: 2:11 PM
 */

namespace App\Guards;
use Framework\Guard;


class AdminAutenticated implements Guard
{
    public function handle(array $params = null)
    {
        session_start();

        if(!isset($_SESSION['adminStatus']))
        {
            $this->reject();
        }
    }

    public function reject()
    {
        header("Location: /");
    }
}