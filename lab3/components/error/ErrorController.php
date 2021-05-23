<?php

require_once(COMPONENT_ERROR . 'ErrorView.php');
require_once(COMPONENT_BASE . 'Controller.php');

class ErrorController extends Controller 
{
    public function __construct() 
    {
        $this->view = new ErrorView();
    }

    public function errorPage() 
    {
        $this->pageData['title'] = "Страница не найдена";
        $this->view->render($this->pageData);
    }
}