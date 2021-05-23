<?php

require_once(COMPONENT_LOGOUT . 'LogoutModel.php');
require_once(COMPONENT_LOGOUT . 'LogoutView.php');
require_once(COMPONENT_BASE . 'Controller.php');

class LogoutController extends Controller 
{
    public function __construct() 
    {
        $roleID = $this->getRole();
        
        $this->model = new LogoutModel($roleID);
        $this->view = new LogoutView();
    }

    public function logout() 
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $data = json_decode($_COOKIE['logged_user'], true);

        $this->model->addLog($data['id'], $ip, "logout", NULL);

        setcookie("logged_user", "", time() - 3600);
        $this->view->render($this->pageData);
    }
}