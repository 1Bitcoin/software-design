<?php

require_once(COMPONENT_BASE . 'Model.php');
require_once(CONNECTION . 'Connection.php');
require_once(ROOT . '/service/Logger.php');

class LoginModel extends Model 
{
    protected $connection;

    public function __construct(UserRepository $userRepository, $roleID) 
    {
        $this->connection = new Connection($roleID);
        $this->repo = $userRepository;

        $this->logger = new Logger();
    }
    
    public function loginUser($infoUser)
    {
        $answer = array();

        if ($infoUser['email'] == "")
        {
            $answer['errors'] = "Введите email!";
            return $answer;
        }

        if ($infoUser['hash_password'] == "")
        {
            $answer['errors'] = "Введите пароль!";
            return $answer;
        }

        $infoUser['hash_password'] = md5($infoUser['hash_password']);

        // Существует ли пользователь с таким email.
        $result = $this->repo->checkExistsUser($infoUser);

        if ($result['nums'])
        {
            // Если существует, то проверить подходит ли пароль.
            $response = $this->repo->checkCoincidenceUser($infoUser);
            
            if (isset($response['user']))
            {
                // Если пароль верный, авторизуем.
                $answer['userInfo'] = $response['user'];
                $this->addLog($answer['userInfo']['id'], $infoUser['ip'], "login", NULL);

            }
            else
            {
                $answer['errors'] = "Неверный пароль!";
            }          
        }
        else
        {
            $answer['errors'] = "Пользователь не зарегестрирован!";
        }

        return $answer;
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