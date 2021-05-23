<?php

require_once(ROOT . '/repository/role/RoleRepositoryInterface.php');

use \RedBeanPHP\R as R;

class RoleRepository implements RoleRepositoryInterface
{
    public function __construct()
    {

    }

    public function getRoleById($id)
    {
        $role = R::getAll('SELECT * FROM `role` WHERE `id` = ?', [$id]);

        return $role[0];
    }
}