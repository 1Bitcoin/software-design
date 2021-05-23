<?php

require_once(COMPONENT_UPLOAD . 'UploadModel.php');
require_once(COMPONENT_UPLOAD . 'UploadView.php');
require_once(COMPONENT_BASE . 'Controller.php');

class UploadController extends Controller 
{
    public function __construct() 
    {
        // Необходимо для авторизации на уровне БД
        $roleID = $this->getRole();
        
        $fileRepository = new FileRepository();

        $this->model = new UploadModel($fileRepository, $roleID);
        $this->view = new UploadView();
    }

    public function uploadFile()
    {
        $dataFiles = $_FILES;
        $data = json_decode($_COOKIE['logged_user'], true);

        $dataFiles['user_id'] = $data['id'];
        $ip = $_SERVER['REMOTE_ADDR'];

        $infoFile = $this->model->uploadFile($dataFiles);

        if (empty($infoFile['file']['error']))
        {
            $infoFile['ip'] = $ip;
            $this->model->addFile($infoFile);
        }
    }
}