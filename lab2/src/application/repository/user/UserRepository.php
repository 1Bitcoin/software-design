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
 
        if (isset($user[0]))
        {
            $result['user'] = $user[0];
        }

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
        
        return $user->id;
    }

    public function updateScoreUser($infoScore)
    {
        $user_id_received = $infoScore['user_id_received'];
        $sumScore = $infoScore['sum_score'];

        $user = R::load('user', $user_id_received);
        $user->raiting = $sumScore;
        R::store($user);
        
        return $user->id;
    }

    public function deleteUser($infoUser)
    {
        $idUser = $infoUser['delete_user_id'];
        R::trashBatch('user', [$idUser]);

        return 0;
    }
}