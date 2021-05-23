<?php

require_once(COMPONENT_LOGGING . 'LoggingView.php');
require_once(COMPONENT_LOGGING . 'LoggingModel.php');
require_once(COMPONENT_BASE . 'Controller.php');
require_once(COMPONENT_ERROR . 'ErrorView.php');

class LoggingController extends Controller 
{
    public $errorView;
    public function __construct() 
    {
        $roleID = $this->getRole();

        $this->model = new LoggingModel($roleID);
        $this->view = new LoggingView();
        $this->errorView = new ErrorView();
    }

    public function getLogs()
    {   
        if (isset($_COOKIE['logged_user']))
        {
            $data = json_decode($_COOKIE['logged_user'], true);
            $role_id = $data['role_id'];

            if ($role_id == 3)
            {
                $this->pageData = $this->model->getLogs();
                //print_r($this->pageData);
                $this->view->render($this->pageData);
            }
            else
            {
                $this->errorView->render($this->pageData);
            }
        }
        else
        {
            $this->errorView->render($this->pageData);
        }
    }
}