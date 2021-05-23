<?php

require_once(COMPONENT_BASE . 'View.php');

class LoggingView extends View
{
    public function render($pageData) 
    {
        $pageTpl = '/public/logging.tpl.php';
        include ROOT . $pageTpl;
    }
}