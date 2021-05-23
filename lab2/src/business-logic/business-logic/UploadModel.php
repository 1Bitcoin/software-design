<?php

require_once(COMPONENT_BASE . 'Model.php');
require_once(CONNECTION . 'Connection.php');
require_once(ROOT . '/service/Logger.php');

class UploadModel extends Model 
{
    protected $connection;

    public function __construct(FileRepository $fileRepository, $roleID) 
    {
        $this->connection = new Connection($roleID);
        $this->repo = $fileRepository;

        $this->logger = new Logger();
    }
    
    public function uploadFile($dataFile)
    {
        $messages = '';
        $infoFile = array();

        if (isset($dataFile['file']) && $dataFile['file']['error'] === UPLOAD_ERR_OK)
        {
            // Получаем информацию о загруженном файле
            $fileTmpPath = $dataFile['file']['tmp_name'];
            $fileName = $dataFile['file']['name'];
            $fileSize = $dataFile['file']['size'];
            $fileType = $dataFile['file']['type'];
            $fileUser = $dataFile['user_id'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            // Изменяем имя файла для хранения
            $newFileName = md5(time() . $fileName);

            // Директория загрузки файлов
            $destPath = UPLOAD_PATH . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) 
            {
                $infoFile['name'] = $fileName; 
                $infoFile['size'] = $fileSize; 
                $infoFile['type'] = $fileType; 
                $infoFile['hash'] = $newFileName;
                $infoFile['user_id'] = $fileUser;
            }
            else 
            {
                $infoFile['file']['error'] = 'Возникла проблема с записью файла в директорию. На директории должны быть права записи.';
            }
        }
        else
        {
            $message = 'Ошибка во время загрузки файла. Изучите возникшую ошибку.';
            $message .= ' Error: ' . $dataFile['file']['error'];
            $infoFile['file']['error'] = $message;
            print_r($message);
        }

        return $infoFile;
    }  

    public function addFile($infoFile)
    {
        if (isset($infoFile['name']))
        {
            $idFile = $this->repo->addFile($infoFile);  
            $this->addLog($infoFile['user_id'], $infoFile['ip'], "upload file", $idFile);
        } 
    }

    public function addLog($user, $ip, $action, $object_id)
    {
        $infoLog = array();

        $infoLog['user_id'] = $user; 
        $infoLog['ip'] = $ip; 
        $infoLog['action'] = $action; 
        $infoLog['object_id'] = $object_id; 

        $this->logger->addLog($infoLog);
    }
}



