<?php

require_once(COMPONENT_BASE . 'View.php');

class ErrorView extends View
{
    public function render($pageData) 
    {
        $pageTpl = '/public/error.tpl.php';
        include ROOT. $pageTpl;
    }
}