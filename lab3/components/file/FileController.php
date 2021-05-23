<?php

require_once(COMPONENT_FILE . 'FileModel.php');
require_once(COMPONENT_FILE . 'FileView.php');
require_once(COMPONENT_ERROR . 'ErrorView.php');
require_once(COMPONENT_BASE . 'Controller.php');
require_once(REPOSITORY . 'file/FileRepository.php');
require_once(REPOSITORY . 'user/UserRepository.php');
require_once(REPOSITORY . 'role/RoleRepository.php');
require_once(REPOSITORY . 'comment/CommentRepository.php');
require_once(REPOSITORY . 'score/ScoreRepository.php');

class FileController extends Controller 
{
    public $errorView;
    public function __construct() 
    {
        // Необходимо для авторизации на уровне БД
        $roleID = $this->getRole();

        $fileRepository = new FileRepository();
        $userRepository = new UserRepository();
        $roleRepository = new RoleRepository();
        $commentRepository = new CommentRepository();
        $scoreRepository = new ScoreRepository();  

        $this->model = new FileModel($fileRepository, $userRepository, $roleRepository, 
                                    $commentRepository, $scoreRepository, $roleID);

        $this->view = new FileView();
        $this->errorView = new ErrorView();
    }

    public function showFiles() 
    {
        $limit = 15;
        $page = $_GET['page'];
 
        $this->pageData = $this->model->filesPagination($limit, $page);  
        $this->view->render($this->pageData);
    }

    public function processingRequest() 
    {
        if (isset($_POST['comment']) && isset($_GET['hash']))
        {
            $this->addCommentFile();
        }
        else if (isset($_POST['score_file']) && isset($_GET['hash']))
        {
            $this->setScoreFile();
        }
        else if (isset($_POST['score_user']) && isset($_GET['hash']))
        {
            $this->setScoreUser();
        }
        else if (isset($_POST['delete_comment']) && isset($_GET['hash']))
        {
            $this->deleteComment();
        }
        else if (isset($_POST['delete_file']))
        {
            $this->deleteFile();
        }
        else if (isset($_POST['delete_user']))
        {
            $this->deleteUser();
        }
        else if (isset($_GET['hash']))
        {
            $this->getFile();
        }
        else
        {
            $this->errorView->render($this->pageData);
        }
    }

    public function getFile()
    {
        if (isset($_GET['hash']))
        {
            $hash = $_GET['hash'];

            // Получаем информацию о файле.
            $this->pageData = $this->model->getFileByHash($hash);
    
            if (empty($this->pageData['error']))
            {
                // Если файл получили - получаем комментарии к нему.
                $this->pageData['info']['comment'] = $this->model->getCommentFile($this->pageData['info']['file']['id']);
                
                // Если пользователь авторизирован - вывести соответствующую страницу файла.
                if (isset($_COOKIE['logged_user']))
                {
                    $data = json_decode($_COOKIE['logged_user'], true);

                    $this->pageData['info']['session']['id'] = $data['id'];
                    $this->pageData['info']['session']['role_id'] = $data['role_id'];
                    $this->view->filePage($this->pageData['info']);
                } 
                else
                {
                    $this->view->filePageGuest($this->pageData['info']);
                }
            }
            else
            {
                $this->errorView->render($this->pageData['error']);
            }
        }
        else
        {
            $this->errorView->render($this->pageData);
        }
    }

    public function addCommentFile()
    {
        $hash = $_GET['hash'];
        $ip = $_SERVER['REMOTE_ADDR'];
        $data = json_decode($_COOKIE['logged_user'], true);

        // Получаем данные из формы
        $infoComment['comment'] = htmlspecialchars($_POST['comment']);
        $infoComment['hash_file'] = $hash;
        $infoComment['user_email'] = $data['email'];
        $infoComment['ip'] = $ip;

        $status = $this->model->addCommentFile($infoComment);

        // Переадресация на страницу файла
        $this->view->redirectionToFile($hash);
    }

    public function setScoreFile()
    {
        $hash = $_GET['hash'];
        $ip = $_SERVER['REMOTE_ADDR'];
        $data = json_decode($_COOKIE['logged_user'], true);

        $infoScore['hash_file'] = $hash;
        $infoScore['value'] = $_POST['score_file'];
        $infoScore['user_email'] = $data['email'];
        $infoScore['ip'] = $ip;

        $this->model->setScoreFile($infoScore);

        // Переадресация на страницу файла
        $this->view->redirectionToFile($hash);
    }

    public function setScoreUser()
    {
        $hash = $_GET['hash'];
        $ip = $_SERVER['REMOTE_ADDR'];
        $data = json_decode($_COOKIE['logged_user'], true);

        $infoScore['hash_file'] = $hash;
        $infoScore['value'] = $_POST['score_user'];
        $infoScore['user_id'] = $data['id'];
        $infoScore['ip'] = $ip;

        $this->model->setScoreUser($infoScore);

        // Переадресация на страницу файла
        $this->view->redirectionToFile($hash);
    }

    public function deleteComment()
    {
        $hash = $_GET['hash'];
        $ip = $_SERVER['REMOTE_ADDR'];
        $data = json_decode($_COOKIE['logged_user'], true);

        $infoComment['comment_id'] = $_POST['delete_comment'];
        $infoComment['user_id'] = $data['id'];
        $infoComment['role_id'] = $data['role_id'];
        $infoComment['ip'] = $ip;

        $this->model->deleteComment($infoComment);

        // Переадресация на страницу файла
        $this->view->redirectionToFile($hash);
    }

    public function deleteFile()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $data = json_decode($_COOKIE['logged_user'], true);

        $infoFile['file_id'] = $_POST['delete_file'];
        $infoFile['user_id'] = $data['id'];
        $infoFile['role_id'] = $data['role_id'];
        $infoFile['ip'] = $ip;

        $this->model->deleteFile($infoFile);

        // Переадресация на список файлов
        $this->view->redirectionToListFiles();
    }

    public function deleteUser()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $data = json_decode($_COOKIE['logged_user'], true);

        $infoUser['delete_user_id'] = $_POST['delete_user'];
        $infoUser['user_id'] = $data['id'];
        $infoUser['role_id'] = $data['role_id'];
        $infoUser['ip'] = $ip;

        $this->model->deleteUser($infoUser);

        // Переадресация на список файлов
        $this->view->redirectionToListFiles();
    }
}