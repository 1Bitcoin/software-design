<?php

require_once(COMPONENT_BASE . 'Model.php');
require_once(CONNECTION . 'Connection.php');
require_once(ROOT . '/service/Logger.php');

class RegisterModel extends Model 
{
    protected $connection;

    public function __construct(UserRepository $userRepository, $roleID) 
    {
        $this->connection = new Connection($roleID);
        $this->repo = $userRepository;

        $this->logger = new Logger();
    }

    public function registerUser($infoUser)
    {
        $answer = array();

        if ($infoUser['email'] == "")
        {
            $answer['error'] = "Введите email!";
            return $answer;
        }

        if ($infoUser['name'] == "")
        {
            $answer['error'] = "Введите name!";
            return $answer;
        }

        if ($infoUser['hash_password'] == "")
        {
            $answer['error'] = "Введите пароль!";
            return $answer;
        }

        

        $result = $this->repo->checkExistsUser($infoUser); 

        if (!$result['nums'])
        {          
            
            if ($infoUser['hash_password'] == $infoUser['repeat_hash_password'])
            {
                $infoUser['hash_password'] = md5($infoUser['hash_password']);
                $infoUser['repeat_hash_password'] = md5($infoUser['repeat_hash_password']);
                
                $idUser = $this->repo->addUser($infoUser);
                
                $this->addLog($idUser, $infoUser['ip'], "register", NULL);

                $answer['user'] = $this->repo->getUserById($idUser);
            }
            else
            {
                // Введенные пароли не совпадают
                $answer['error'] = "Пароли не совпадают!";
            }
        }
        else
        {
            // Пользователь уже зарегистрирован
            $answer['error'] = "Пользователь уже зарегистрирован!";
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