<?php

require_once(COMPONENT_LOGIN . 'LoginModel.php');
require_once(COMPONENT_LOGIN . 'LoginView.php');
require_once(COMPONENT_BASE . 'Controller.php');
require_once(REPOSITORY . 'user/UserRepository.php');


class LoginController extends Controller 
{
    public function __construct() 
    {
        // Необходимо для авторизации на уровне БД
        $roleID = $this->getRole();

        $userRepository = new UserRepository();

        $this->model = new LoginModel($userRepository, $roleID);

        $this->view = new LoginView();
    }

    public function loginPage() 
    {
        if (isset($_POST['login']))
        {
            $this->login();
        }
        else
        {
            // Иначе отображаем форму для авторизации, если пользователь не авторизован.
            if (isset($_COOKIE['logged_user']))
            {
                $this->view->main($this->pageData);
            }
            else
            {
                $this->view->render($this->pageData);
            } 
        }
    }

    public function login()
    {
        // Получаем данные из формы, экранируем(для безопасности), пароли хэшируем.
        $infoUser['email'] = htmlspecialchars($_POST['email']);
        $infoUser['hash_password'] = htmlspecialchars($_POST['password']);

        $ip = $_SERVER['REMOTE_ADDR'];
        $infoUser['ip'] = $ip;

        $this->pageData = $this->model->loginUser($infoUser);

       if (empty($this->pageData['errors']))
        {
            $value = $this->pageData['userInfo'];
            setcookie('logged_user', json_encode($value), time()+3600, "/");
            
            $this->view->main($this->pageData);
        }
        else
        {
            $this->view->render($this->pageData);
        }
    }
}