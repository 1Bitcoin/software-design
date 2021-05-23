<?php

interface UserRepositoryInterface
{

    public function getUserIdByEmail($email);

    public function getUserById($id);

    public function checkCoincidenceUser($infoUser);

    public function checkExistsUser($infoUser);

    public function addUser($infoUser);
    
}