<?php

require_once(COMPONENT_BASE . 'View.php');

class LogoutView extends View
{
    public function render($pageData) 
    {
        header("Location: /");
    }
}