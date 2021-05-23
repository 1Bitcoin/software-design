<?php

require_once(COMPONENT_BASE . 'View.php');

class LoginView extends View
{
    public function render($pageData) 
    {
        $pageTpl = '/public/login.tpl.php';
        include ROOT. $pageTpl;
    }

    public function main($pageData) 
    {
        header("Location: /");
    }
}