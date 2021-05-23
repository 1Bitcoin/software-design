<?php

class Controller 
{
    public $model;
    public $view;
    protected $pageData = array();

    public function __construct() 
    {
        $this->view = new View();
        $this->model = new Model();
    }	

    public function getRole()
    {
        // roleID = 0 - гость
        $roleID = 0;

        if (isset($_COOKIE['logged_user']))
        {
            $data = json_decode($_COOKIE['logged_user'], true);
            $roleID = $data['role_id'];
        }

        return $roleID;
    }
}