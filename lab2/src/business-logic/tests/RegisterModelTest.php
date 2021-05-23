<?php

use PHPUnit\Framework\TestCase;

require_once("/var/www/course-project-database/config/Config.php");

require_once(COMPONENT_REGISTER . 'RegisterModel.php');
require_once(ROOT . '/service/Logger.php');

class RegisterModelTest extends TestCase
{ 
    public function testRegisterUserNoEmail()
    {
        $logger = $this->getMockBuilder(Logger::class)
        ->disableOriginalConstructor() 
        ->getMock();

        $userRepository = $this->getMockBuilder(UserRepository::class)
        ->disableOriginalConstructor()
        ->getMock();

        $registerModel = new RegisterModel($userRepository, 0);
        $registerModel->logger = $logger;

        $infoUser['email'] = "";
        $infoUser['name'] = "234";
        $infoUser['hash_password'] = "fgh65867876586578";
        $infoUser['repeat_hash_password'] = "fgh65867876586578";
        $infoUser['ip'] = "12.12.12.12";
        $this->assertEquals('Введите email!', $registerModel->registerUser($infoUser)['error']);
    }

    public function testRegisterUserNoName()
    {
        $logger = $this->getMockBuilder(Logger::class)
        ->disableOriginalConstructor() 
        ->getMock();

        $userRepository = $this->getMockBuilder(UserRepository::class)
        ->disableOriginalConstructor()
        ->getMock();

        $registerModel = new RegisterModel($userRepository, 0);
        $registerModel->logger = $logger;

        $infoUser['email'] = "asa@mail.ru";
        $infoUser['name'] = "";
        $infoUser['hash_password'] = "fgh65867876586578";
        $infoUser['repeat_hash_password'] = "fgh65867876586578";
        $infoUser['ip'] = "12.12.12.12";
        $this->assertEquals('Введите name!', $registerModel->registerUser($infoUser)['error']);
    }

    public function testRegisterUserNoPassword()
    {
        $logger = $this->getMockBuilder(Logger::class)
        ->disableOriginalConstructor() 
        ->getMock();

        $userRepository = $this->getMockBuilder(UserRepository::class)
        ->disableOriginalConstructor()
        ->getMock();

        $registerModel = new RegisterModel($userRepository, 0);
        $registerModel->logger = $logger;

        $infoUser['email'] = "asa@mail.ru";
        $infoUser['name'] = "dima";
        $infoUser['hash_password'] = "";
        $infoUser['repeat_hash_password'] = "";
        $infoUser['ip'] = "12.12.12.12";
        $this->assertEquals('Введите пароль!', $registerModel->registerUser($infoUser)['error']);
    }

    public function testRegisterUserAlreadyRegistered()
    {
        $logger = $this->getMockBuilder(Logger::class)
        ->disableOriginalConstructor() 
        ->getMock();

        $userRepository = $this->getMockBuilder(UserRepository::class)
        ->setMethods(['checkExistsUser'])
        ->getMock();
    
        $result['nums'] = 1;
        $userRepository->expects($this->once())
        ->method('checkExistsUser')
        ->will($this->returnValue($result));

        $registerModel = new RegisterModel($userRepository, 0);
        $registerModel->logger = $logger;

        $infoUser['email'] = "shi@mail.ru";
        $infoUser['name'] = "ivan";
        $infoUser['hash_password'] = "asadf";
        $infoUser['repeat_hash_password'] = "asadf";
        $infoUser['ip'] = "12.12.12.12";
        $this->assertEquals('Пользователь уже зарегистрирован!', $registerModel->registerUser($infoUser)['error']);
    }

    public function testRegisterUserRepeatPasswordIncorrect()
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

        $registerModel = new RegisterModel($userRepository, 0);
        $registerModel->logger = $logger;

        $infoUser['email'] = "asa@mail.ru";
        $infoUser['name'] = "dima";
        $infoUser['hash_password'] = "asadf";
        $infoUser['repeat_hash_password'] = "67";
        $infoUser['ip'] = "12.12.12.12";
        $this->assertEquals('Пароли не совпадают!', $registerModel->registerUser($infoUser)['error']);
    }
}