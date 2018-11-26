<?php

class UserController
{
    public function getUserByIdAction($id)
    {
        echo "Sunt userul cu id-ul: ";
        echo $id[0];
    }

    public function addUserAction()
    {
        echo "New user added";
    }

    public function deleteUserAction($id)
    {
        echo "A fost sters user-ul cu id: ";
        echo $id[0];
    }
}