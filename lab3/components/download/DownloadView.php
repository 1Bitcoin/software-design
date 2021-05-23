<?php

require_once(COMPONENT_BASE . 'View.php');

class DownloadView extends View
{
    public function render($pageData) 
    {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $pageData['name']);
        exit(readfile(UPLOAD_PATH . $pageData['hash']));
    }
}