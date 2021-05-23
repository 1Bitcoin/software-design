<?php

require_once(COMPONENT_BASE . 'View.php');

class FileView extends View
{
    public function render($pageData) 
    {
        $pageTpl = '/public/list.tpl.php';
        include ROOT . $pageTpl;
    }

    public function filePage($pageData) 
    {
        $pageTpl = '/public/file.tpl.php';
        include ROOT . $pageTpl;
    }

    public function filePageGuest($pageData) 
    {
        $pageTpl = '/public/file-guest.tpl.php';
        include ROOT . $pageTpl;
    }
    
    public function redirectionToFile($hash) 
    {
        $url = "/file?hash=" . $hash;
        header("Location: $url");
    }

    public function redirectionToListFiles() 
    {
        header("Location: /list?page=1");
    }
}