<?php

require_once(ROOT . '/repository/user/UserRepositoryInterface.php');

use \RedBeanPHP\R as R;

class UserRepository implements UserRepositoryInterface
{
    public function __construct()
    {

    }
    
    public function getUserIdByEmail($email)
    {
        $user = R::getAll('SELECT * FROM `user` WHERE `email` = ?', [$email]);
        
        return $user[0];
    }

    public function getUserById($id)
    {
        $user = R::getAll('SELECT * FROM `user` WHERE `id` = ?', [$id]);
        
        return $user[0];
    }

    public function checkCoincidenceUser($infoUser)
    {
        $email = $infoUser['email'];
        $hashPassword = $infoUser['hash_password'];

        $user = R::getAll('SELECT * FROM `user` WHERE `email` = ? AND `hash_password` = ?', [$email, $hashPassword]);
        $nums = R::count('user', 'email = ? AND hash_password = ?', [$email, $hashPassword]);
 
        $result['response'] = $user[0];
        $result['nums'] = $nums;   
        
        return $result;
    }

    public function checkExistsUser($infoUser)
    {
        $email = $infoUser['email'];

        $nums = R::count('user', 'email = ?', [$email]); 
        $result['nums'] = $nums;   
        
        return $result;
    }

    public function addUser($infoUser)
    {
        $email = $infoUser['email'];
        $name = $infoUser['name'];
        $hashPassword = $infoUser['hash_password'];

        $user = R::dispense('user');

        // Заполняем объект свойствами
        $user->email = $email;
        $user->name = $name;
        $user->hash_password = $hashPassword;

        // Сохраняем объект
        R::store($user); 
        
        return 0;
    }
}