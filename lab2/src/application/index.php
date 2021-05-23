<?php

require_once("/root/technologicalUI/config/Config.php");
require_once(REPOSITORY . 'file/FileRepository.php');
require_once(REPOSITORY . 'user/UserRepository.php');
require_once(REPOSITORY . 'role/RoleRepository.php');
require_once(REPOSITORY . 'comment/CommentRepository.php');
require_once(REPOSITORY . 'score/ScoreRepository.php');
require(COMPONENT_FILE . "FileModel.php");
require(COMPONENT_REGISTER . "RegisterModel.php");
require(COMPONENT_LOGIN . "LoginModel.php");

class TechnologicalUI 
{
    public $roleID;
    public $email;
    public $user_id;

    public $fileModel;
    public $registerModel;

    public $fileRepository;
    public $userRepository;
    public $roleRepository;
    public $commentRepository;
    public $scoreRepository;

    public function __construct() 
    {
        $this->roleID = 0;
        $this->connection = new Connection(3);

        $this->fileRepository = new FileRepository();
        $this->userRepository = new UserRepository();
        $this->roleRepository = new RoleRepository();
        $this->commentRepository = new CommentRepository();
        $this->scoreRepository = new ScoreRepository();  

        $this->fileModel = new FileModel($this->fileRepository, $this->userRepository, $this->roleRepository, 
                                        $this->commentRepository, $this->scoreRepository);
                        
        $this->registerModel = new RegisterModel($this->userRepository);
        $this->loginModel = new LoginModel($this->userRepository);
    }	

    public function showMenu()
    {
        $exit = FALSE;

        while(!$exit)
        {
            echo("\n~~~~~Menu~~~~~\n");
            
            echo("\n1) Exit\n");
            echo("\n2) Get file by hash\n");
            echo("\n3) Register user\n");
            echo("\n4) Login user\n");
            echo("\n5) Get list files\n");
            echo("\n6) Add comment to file\n");
            echo("\n7) Delete comment\n");
            echo("\n8) Delete user\n");
            echo("\n9) Delete file\n");
            echo("\n10) Increase score file\n");
            echo("\n11) Descrease score file\n");
            echo("\n12) Increase score user\n");
            echo("\n13) Decrease score user\n");
            echo("\n14) Get comment file\n");


            $choice = readline("Input your choice:"); 
        
            switch ($choice) 
            {
                case 1:
                    $exit = $this->myExit();
                    break;

                case 2:
                    $this->getFileByHash();
                    break;

                case 3:
                    $this->registerUser();
                    break;

                case 4:
                    $this->loginUser();
                    break;

                case 5:
                    $this->getListFiles();
                    break;

                case 6:
                    $this->addComment();
                    break;

                case 7:
                    $this->deleteComment();
                    break;

                case 8:
                    $this->deleteUser();
                    break;

                case 9:
                    $this->deleteFile();
                    break;

                case 10:
                    $this->setScoreIncreaseFile();
                    break;

                case 11:
                    $this->setScoreDecreaseFile();
                    break;

                case 12:
                    $this->setScoreIncreaseUser();
                    break;

                case 13:
                    $this->setScoreDecreaseUser();
                    break;

                case 14:
                    $this->getCommentFile();
                    break;
            }
        }
    }

    public function getCommentFile()
    {
        $idFile = $this->getInfoCommentFile();

        $comments = $this->fileModel->getCommentFile($idFile);

        foreach ($comments as $comment) 
        {
            print_r($comment);
        }
    }

    public function getInfoCommentFile()
    {
        $idFile= readline("\nInput file id:"); 
        return $idFile;
    }

    public function setScoreDecreaseUser()
    {
        $infoScore = $this->getInfoScoreDecreaseUser();

        if ($this->roleID > 0)
        {
            $this->fileModel->setScoreUser($infoScore);
            echo("Рейтинг пользователя уменьшен");
        }
        else
        {
            echo("Недостаточно прав");
        }
    }

    public function getInfoScoreDecreaseUser()
    {
        $infoScore['user_id_received'] = readline("\nInput user id:"); 
        $infoScore['user_id'] = $this->user_id; 
        $infoScore['value'] = -1; 

        return $infoScore;
    }

    public function setScoreIncreaseUser()
    {
        $infoScore = $this->getInfoScoreIncreaseUser();

        if ($this->roleID > 0)
        {
            $this->fileModel->setScoreUser($infoScore);
            echo("Рейтинг пользователя увеличен");
        }
        else
        {
            echo("Недостаточно прав");
        }
    }

    public function getInfoScoreIncreaseUser()
    {
        $infoScore['user_id_received'] = readline("\nInput user id:"); 
        $infoScore['user_id'] = $this->user_id; 
        $infoScore['value'] = 1; 

        return $infoScore;
    }

    public function setScoreDecreaseFile()
    {
        $infoScore = $this->getInfoScoreDecreaseFile();

        if ($this->roleID > 0)
        {
            $this->fileModel->setScoreFile($infoScore);
            echo("Рейтинг файла уменьшен");
        }
        else
        {
            echo("Недостаточно прав");
        }
    }

    public function getInfoScoreDecreaseFile()
    {
        $infoScore['hash_file'] = readline("\nInput file hash:"); 
        $infoScore['user_email'] = $this->email;
        $infoScore['value'] = -1; 

        return $infoScore;
    }

    public function setScoreIncreaseFile()
    {
        $infoScore = $this->getInfoScoreIncreaseFile();

        if ($this->roleID > 0)
        {
            $this->fileModel->setScoreFile($infoScore);
            echo("Рейтинг файла увеличен");
        }
        else
        {
            echo("Недостаточно прав");
        }
    }

    public function getInfoScoreIncreaseFile()
    {
        $infoScore['hash_file'] = readline("\nInput file hash:"); 
        $infoScore['user_email'] = $this->email; ; 
        $infoScore['value'] = 1; 

        return $infoScore;
    }

    public function deleteUser()
    {
        $infoUser = $this->getInfoDeleteUser();

        if ($this->roleID > 2)
        {
            $this->fileModel->deleteUser($infoUser);
            echo("Пользователь удален");
        }
        else
        {
            echo("Недостаточно прав");
        }
    }

    public function getInfoDeleteUser()
    {
        $infoUser['delete_user_id'] = readline("\nInput user id:"); 

        return $infoUser;
    }

    public function deleteComment()
    {
        $infoComment = $this->getInfoDeleteComment();

        if ($this->roleID > 1)
        {
            $this->fileModel->deleteComment($infoComment);
            echo("Комментарий удален");
        }
        else
        {
            echo("Недостаточно прав");
        }
    }

    public function getInfoDeleteComment()
    {
        $infoComment['comment_id'] = readline("\nInput comment id:"); 

        return $infoComment;
    }

    public function addComment()
    {
        $infoComment = $this->getInfoComment();

        if ($this->roleID > 0)
        {
            $this->fileModel->addCommentFile($infoComment);
        }
        else
        {
            echo("Недостаточно прав");
        }
    }

    public function getInfoComment()
    {
        $infoComment['hash_file'] = readline("\nInput hash file:"); 
        $infoComment['comment'] = readline("\nInput comment:");  
        $infoComment['user_email'] = $this->email; 

        return $infoComment;
    }

    public function getListFiles()
    {
        $limit = $this->getLimitCountFiles();
        $answer = $this->fileModel->filesPagination($limit);

        foreach ($answer['files'] as $file) 
        {
            print_r($file);
        }
    }

    public function getLimitCountFiles()
    {
        $limit = readline("\nInput limit files:"); 

        return $limit;
    }

    public function loginUser()
    {
        $infoUser = $this->getInfoLoginUser();
        $answer = $this->loginModel->loginUser($infoUser);

        if (isset($answer['errors']))
        {
            echo($answer['errors']);
        }
        else
        {
            $this->roleID = $answer['userInfo']['role_id'];
            $this->email = $answer['userInfo']['email'];
            $this->user_id = $answer['userInfo']['id'];
            echo("Пользователь успешно авторизовался");
        }
    }

    public function getInfoLoginUser()
    {
        $infoUser['email'] = readline("\nInput email:"); 
        $infoUser['hash_password'] = readline("\nInput password:"); 

        return $infoUser;
    }

    public function registerUser()
    {
        $infoUser = $this->getInfoRegisterUser();
        $answer = $this->registerModel->registerUser($infoUser);

        if (isset($answer['error']))
        {
            echo($answer['error']);
        }
        else
        {
            echo("Пользователь успешно зарегистрировался");
        }

    }

    public function getInfoRegisterUser()
    {
        $infoUser['email'] = readline("\nInput email:"); 
        $infoUser['name'] = readline("\nInput name:"); 
        $infoUser['hash_password'] = readline("\nInput password:"); 
        $infoUser['repeat_hash_password'] = readline("\nInput repeat password:"); 

        return $infoUser;
    }

    public function getFileByHash()
    {
        $hash = $this->getHash();
        $infoFile = $this->fileModel->getFileByHash($hash);

        if (isset($infoFile['info']))
        {
            echo("\nFile: \n");
            foreach ($infoFile['info']['file'] as $key => $value) 
            {
                echo "{$key} => {$value} \n";
            }
            
            echo("\nOwner: \n");
            foreach ($infoFile['info']['user'] as $key => $value) 
            {
                echo "{$key} => {$value} \n";
            }
        }
        else
        {
            echo("\nФайла с таким хешем нет на сервере\n");
        }
    }

    public function getHash()
    {
        $hash = readline("\nInput file hash:"); 

        return $hash;
    }

    public function myExit()
    {
	    return TRUE;
    }
}

$technologicalUI = new TechnologicalUI();
$technologicalUI->showMenu();