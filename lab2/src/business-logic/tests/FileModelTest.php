<?php

use PHPUnit\Framework\TestCase;

require_once("/var/www/course-project-database/config/Config.php");

require_once(COMPONENT_FILE . 'FileModel.php');
require_once(ROOT . '/service/Logger.php');

class FileModelTest extends TestCase
{ 

    public function testGetFileByHashIncorrectHash()
    {
        $logger = $this->getMockBuilder(Logger::class)
        ->disableOriginalConstructor() 
        ->getMock();

        $userRepository = $this->getMockBuilder(UserRepository::class)
        ->disableOriginalConstructor()
        ->getMock();

        $fileRepository = $this->getMockBuilder(FileRepository::class)
        ->disableOriginalConstructor()
        ->setMethods(['getFileByHash'])
        ->getMock();

        $fileRepository->expects($this->once())
        ->method('getFileByHash')
        ->will($this->returnValue(null));

        $commentRepository = $this->getMockBuilder(CommentRepository::class)
        ->disableOriginalConstructor()
        ->getMock();

        $roleRepository = $this->getMockBuilder(RoleRepository::class)
        ->disableOriginalConstructor()
        ->getMock();

        $scoreRepository = $this->getMockBuilder(ScoreRepository::class)
        ->disableOriginalConstructor()
        ->getMock();

        $fileModel = new FileModel($fileRepository, $userRepository, 
                    $roleRepository, $commentRepository, $scoreRepository, 1);

        $fileModel->logger = $logger;

        $hash = "g1j2k3jk";

        $this->assertEquals("Файла с хэшем g1j2k3jk нет на сервере", $fileModel->getFileByHash($hash)['error']);
    }

    public function testDeleteCommentLowRoleID()
    {
        $logger = $this->getMockBuilder(Logger::class)
        ->disableOriginalConstructor() 
        ->getMock();

        $userRepository = $this->getMockBuilder(UserRepository::class)
        ->disableOriginalConstructor()
        ->getMock();

        $fileRepository = $this->getMockBuilder(FileRepository::class)
        ->disableOriginalConstructor()
        ->getMock();

        $commentRepository = $this->getMockBuilder(CommentRepository::class)
        ->disableOriginalConstructor()
        ->getMock();

        $roleRepository = $this->getMockBuilder(RoleRepository::class)
        ->disableOriginalConstructor()
        ->getMock();

        $scoreRepository = $this->getMockBuilder(ScoreRepository::class)
        ->disableOriginalConstructor()
        ->getMock();

        $fileModel = new FileModel($fileRepository, $userRepository, 
                    $roleRepository, $commentRepository, $scoreRepository, 1);

        $fileModel->logger = $logger;

        $infoComment['role_id'] = "1";

        $this->assertEquals('Недостаточно прав для удаления комментария!', $fileModel->deleteComment($infoComment)['error']);
    }

    public function testDeleteCommentCorrectRoleID()
    {
        $logger = $this->getMockBuilder(Logger::class)
        ->disableOriginalConstructor() 
        ->getMock();

        $userRepository = $this->getMockBuilder(UserRepository::class)
        ->disableOriginalConstructor()
        ->getMock();

        $fileRepository = $this->getMockBuilder(FileRepository::class)
        ->disableOriginalConstructor()
        ->getMock();

        $commentRepository = $this->getMockBuilder(CommentRepository::class)
        ->disableOriginalConstructor()
        ->setMethods(['deleteComment'])
        ->getMock();

        $commentRepository->expects($this->once())
        ->method('deleteComment')
        ->will($this->returnValue(true));

        $roleRepository = $this->getMockBuilder(RoleRepository::class)
        ->disableOriginalConstructor()
        ->getMock();

        $scoreRepository = $this->getMockBuilder(ScoreRepository::class)
        ->disableOriginalConstructor()
        ->getMock();

        $fileModel = new FileModel($fileRepository, $userRepository, 
                    $roleRepository, $commentRepository, $scoreRepository, 1);

        $fileModel->logger = $logger;

        $infoComment['role_id'] = "2";
        $infoComment['user_id'] = "68";
        $infoComment['ip'] = "56.56.56.56";
        $infoComment['comment_id'] = "32";

        $this->assertEquals([], $fileModel->deleteComment($infoComment));
    }
    
    public function testDeleteFileCorrectRoleID()
    {
        $logger = $this->getMockBuilder(Logger::class)
        ->disableOriginalConstructor() 
        ->getMock();

        $userRepository = $this->getMockBuilder(UserRepository::class)
        ->disableOriginalConstructor()
        ->getMock();

        $fileRepository = $this->getMockBuilder(FileRepository::class)
        ->disableOriginalConstructor()
        ->setMethods(['deleteFile'])
        ->getMock();

        $fileRepository->expects($this->once())
        ->method('deleteFile')
        ->will($this->returnValue(true));

        $commentRepository = $this->getMockBuilder(CommentRepository::class)
        ->disableOriginalConstructor()
        ->getMock();

        $roleRepository = $this->getMockBuilder(RoleRepository::class)
        ->disableOriginalConstructor()
        ->getMock();

        $scoreRepository = $this->getMockBuilder(ScoreRepository::class)
        ->disableOriginalConstructor()
        ->getMock();

        $fileModel = new FileModel($fileRepository, $userRepository, 
                    $roleRepository, $commentRepository, $scoreRepository, 1);

        $fileModel->logger = $logger;

        $infoFile['role_id'] = "3";
        $infoFile['user_id'] = "68";
        $infoFile['ip'] = "222.233.53.22";
        $infoFile['file_id'] = "32";

        $this->assertEquals([], $fileModel->deleteFile($infoFile));
    }

    public function testDeleteFileLowRoleID()
    {
        $logger = $this->getMockBuilder(Logger::class)
        ->disableOriginalConstructor() 
        ->getMock();

        $userRepository = $this->getMockBuilder(UserRepository::class)
        ->disableOriginalConstructor()
        ->getMock();

        $fileRepository = $this->getMockBuilder(FileRepository::class)
        ->disableOriginalConstructor()
        ->getMock();

        $commentRepository = $this->getMockBuilder(CommentRepository::class)
        ->disableOriginalConstructor()
        ->getMock();

        $roleRepository = $this->getMockBuilder(RoleRepository::class)
        ->disableOriginalConstructor()
        ->getMock();

        $scoreRepository = $this->getMockBuilder(ScoreRepository::class)
        ->disableOriginalConstructor()
        ->getMock();

        $fileModel = new FileModel($fileRepository, $userRepository, 
                    $roleRepository, $commentRepository, $scoreRepository, 1);

        $fileModel->logger = $logger;

        $infoFile['role_id'] = "1";
        $infoFile['user_id'] = "2";
        $infoFile['ip'] = "166.233.53.22";
        $infoFile['file_id'] = "36";

        $this->assertEquals('Недостаточно прав для удаления файла!', $fileModel->deleteFile($infoFile)['error']);
    }
}