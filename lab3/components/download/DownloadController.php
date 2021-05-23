<?php

require_once(COMPONENT_DOWNLOAD . 'DownloadModel.php');
require_once(COMPONENT_DOWNLOAD . 'DownloadView.php');
require_once(COMPONENT_BASE . 'Controller.php');
require_once(REPOSITORY . 'file/FileRepository.php');

class DownloadController extends Controller 
{
    public function __construct() 
    {
        $roleID = $this->getRole();

        $fileRepository = new FileRepository();
        
        $this->model = new DownloadModel($fileRepository, $roleID);
        $this->view = new DownloadView();
    }

    public function download() 
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $data['id'] = NULL;

        if (isset($_COOKIE['logged_user']))
            $data = json_decode($_COOKIE['logged_user'], true);

        $hash = $_POST['download'];

        $infoUser['user_id'] = $data['id'];
        $infoUser['ip'] = $ip;

        $this->pageData = $this->model->download($hash, $infoUser);
        $this->view->render($this->pageData);
    }
}