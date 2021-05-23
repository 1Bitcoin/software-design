<?php

require_once(COMPONENT_BASE . 'View.php');

class RegisterView extends View
{
    public function render($pageData) 
    {
        $pageTpl = '/public/register.tpl.php';
        include ROOT. $pageTpl;
    }

    public function main($pageData) 
    {
        header("Location: /");
    }
}