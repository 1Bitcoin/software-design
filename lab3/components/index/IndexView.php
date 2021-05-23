<?php

require_once(COMPONENT_BASE . 'View.php');

class IndexView extends View
{
    public function render($pageData) 
    {
        $pageTpl = '/public/main.tpl.php';
        include ROOT . $pageTpl;
    }

    public function renderGuestPage($pageData) 
    {
        $pageTpl = '/public/main-guest.tpl.php';
        include ROOT . $pageTpl;
    }
}