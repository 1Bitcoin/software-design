<?php

use PHPUnit\Framework\TestCase;

require_once("/var/www/course-project-database/config/Config.php");

require_once(COMPONENT_LOGIN . 'LoginModel.php');
require_once(CONNECTION . 'Connection.php');
require_once(ROOT . '/service/Logger.php');

class LoginModelTest extends TestCase
{ 
    protected $result;

    public function testLoginUserInvalidPassword()
    {
        $logger = $this->getMockBuilder(Logger::class)
        ->disableOriginalConstructor()
        ->getMock();

        $userRepository = $this->getMockBuilder(UserRepository::class)
        ->setMethods(['checkExistsUser'])
        ->setMethods(['checkCoincidenceUser'])
        ->getMock();

        $result['nums'] = 1;
        $userRepository->expects($this->once())
        ->method('checkExistsUser')
        ->will($this->returnValue($result));

        $userRepository->expects($this->once())
        ->method('checkCoincidenceUser')
        ->will($this->returnValue($result));

        $loginModel = new LoginModel($userRepository, 1);
        $loginModel->logger = $logger;

        $infoUser['email'] = "567";
        $infoUser['hash_password'] = "hj";
        $infoUser['ip'] = "45.45.45.45";
        $this->assertEquals("Неверный пароль!", $loginModel->loginUser($infoUser)['errors']);
    }

    public function testAddLogNoLogger()
    {
        $logger = $this->getMockBuilder(Logger::class)
        ->disableOriginalConstructor() 
        ->getMock();

        $userRepository = $this->getMockBuilder(UserRepository::class)
        ->disableOriginalConstructor()
        ->getMock();

        $loginModel = $this->getMockBuilder(LoginModel::class)
        ->disableOriginalConstructor()
        ->setMethods(['addLog'])
        ->getMock();

        $loginModel->expects($this->once())
        ->method('addLog')
        ->will($this->returnValue(true));

        $loginModel->repo = $userRepository;
        $loginModel->logger = $logger;

        $infoUser['email'] = "";
        $infoUser['hash_password'] = "dhg56";
        $infoUser['ip'] = "45.45.45.45";
        $this->assertEquals(true, $loginModel->addLog(1, "4.4.4.4", "login", NULL));
    }

    public function testLoginUserNoEmail()
    {
        $logger = $this->getMockBuilder(Logger::class)
        ->disableOriginalConstructor()
        ->getMock();

        $userRepository = $this->getMockBuilder(UserRepository::class)
        ->disableOriginalConstructor()
        ->getMock();

        $loginModel = new LoginModel($userRepository, 1);
        
        $infoUser['email'] = "";
        $infoUser['hash_password'] = "dhg56";
        $infoUser['ip'] = "45.45.45.45";
        $this->assertEquals("Введите email!", $loginModel->loginUser($infoUser)['errors']);
    }

    public function testLoginUserNoPassword()
    {
        $userRepository = $this->getMockBuilder(UserRepository::class)
        ->disableOriginalConstructor()
        ->getMock();

        $loginModel = new LoginModel($userRepository, 1);

        $infoUser['email'] = "567";
        $infoUser['hash_password'] = "";
        $infoUser['ip'] = "45.45.45.45";
        $this->assertEquals("Введите пароль!", $loginModel->loginUser($infoUser)['errors']);
    }

    public function testLoginUserNoRegister()
    {
        $logger = $this->getMockBuilder(Logger::class)
        ->disableOriginalConstructor() 
        ->getMock();

        $userRepository = $this->getMockBuilder(UserRepository::class)
        ->setMethods(['checkExistsUser'])
        ->getMock();

        $result['nums'] = 0;
        $userRepository->expects($this->once())
        ->method('checkExistsUser')
        ->will($this->returnValue($result));

        $loginModel = new LoginModel($userRepository, 1);
        $loginModel->logger = $logger;

        $infoUser['email'] = "567";
        $infoUser['hash_password'] = "hj";
        $infoUser['ip'] = "45.45.45.45";
        $this->assertEquals("Пользователь не зарегестрирован!", $loginModel->loginUser($infoUser)['errors']);
    }
}