<?php

require(COMPONENT_INDEX . "IndexController.php");
require(COMPONENT_LOGIN . "LoginController.php");
require(COMPONENT_UPLOAD . "UploadController.php");
require(COMPONENT_ERROR . "ErrorController.php");
require(COMPONENT_FILE . "FileController.php");
require(COMPONENT_REGISTER . "RegisterController.php");
require(COMPONENT_LOGOUT . "LogoutController.php");
require(COMPONENT_DOWNLOAD . "DownloadController.php");
require(COMPONENT_LOGGING . "LoggingController.php");

class Router
{
    public static function run() 
    {
        $url = parse_url($_SERVER['REQUEST_URI']);

        switch ($url["path"]) 
        {
            case "/":                    
                $controller = new IndexController();
                $controller->indexPage();  
                break;

            case "/login":
                $controller = new LoginController();
                $controller->loginPage();  
                break;

            case "/load":
                $controller = new UploadController();
                $controller->uploadFile();  
                break;  

            case "/register":
                $controller = new RegisterController();
                $controller->registerPage();  
                break; 

            case "/list":
                $controller = new FileController();
                $controller->showFiles();  
                break;  

            case "/file":
                $controller = new FileController();
                $controller->processingRequest();  
                break;  

            case "/logout":
                $controller = new LogoutController();
                $controller->logout();  
                break;  

            case "/download":
                $controller = new DownloadController();
                $controller->download();  
                break; 
                
            case "/logging":
                $controller = new LoggingController();
                $controller->getLogs();  
                break;  

            default:
                $controller = new ErrorController();
                $controller->errorPage();
        }
    }
}