<?php

use PHPUnit\Framework\TestCase;

require_once("/var/www/course-project-database/config/Config.php");

require_once(COMPONENT_UPLOAD . 'UploadModel.php');
require_once(ROOT . '/service/Logger.php');

class UploadModelTest extends TestCase
{ 
    public function testUploadFileErrorUpload()
    {
        $logger = $this->getMockBuilder(Logger::class)
        ->disableOriginalConstructor() 
        ->getMock();

        $fileRepository = $this->getMockBuilder(FileRepository::class)
        ->disableOriginalConstructor()
        ->getMock();

        $uploadModel = new UploadModel($fileRepository, 1);
        $uploadModel->logger = $logger;

        $dataFile = array();
        $dataFile['file']['error'] = "This is error";

        $this->assertEquals('Ошибка во время загрузки файла. Изучите возникшую ошибку. Error: ' .  $dataFile['file']['error'], 
                            $uploadModel->uploadFile($dataFile)['file']['error']);
    }

    public function testUploadFileLockDirectory()
    {
        $logger = $this->getMockBuilder(Logger::class)
        ->disableOriginalConstructor() 
        ->getMock();

        $fileRepository = $this->getMockBuilder(FileRepository::class)
        ->disableOriginalConstructor()
        ->getMock();

        $uploadModel = new UploadModel($fileRepository, 1);
        $uploadModel->logger = $logger;

        $dataFile = array();
        $dataFile['file']['error'] = 0;
        $dataFile['file']['tmp_name'] = "tmp_file";
        $dataFile['file']['name'] = "test";
        $dataFile['file']['size'] = 12;
        $dataFile['file']['type'] = "pdf";
        $dataFile['user_id'] = 12;;

        $this->assertEquals('Возникла проблема с записью файла в директорию. На директории должны быть права записи.', 
                            $uploadModel->uploadFile($dataFile)['file']['error']);
    }
}