<?php

require_once(COMPONENT_BASE . 'Model.php');
require_once(CONNECTION . 'Connection.php');

class FileModel extends Model 
{
    public $connection;
    public $userRepository;
    public $roleRepository;
    public $scoreRepository;
    public $commentRepository;

    public function __construct(FileRepository $fileRepository, UserRepository $userRepository, 
                                RoleRepository $roleRepository, CommentRepository $commentRepository, 
                                ScoreRepository $scoreRepository) 
    {

        $this->repo = $fileRepository;
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
        $this->commentRepository = $commentRepository;
        $this->scoreRepository = $scoreRepository;
    }

    public function filesPagination($limit)
    {
        // Формируем данные для передачи в html форму
        $result['title'] = "Список файлов";
        $result['files'] = $this->repo->getRowsByLimit(0, $limit);    
        $result['limit'] = $limit; 
        $result['page'] = $page;

        // Получить число записей в таблице
        $rows = $this->repo->getCountRows(); 
        $result['count'] = $rows;
        
        return $result;
    }

    public function getFileByHash($hash)
    {
        $answer = array();
        $datePage['file'] = $this->repo->getFileByHash($hash);

        // Из базы вощвращается массив, но т.к файл с определенным хэшем
        // только один - берем нулевой элемент.
        if ($datePage['file'] != NULL)
        {
            $datePage['user'] = $this->userRepository->getUserById($datePage['file']['user_id']);
            $datePage['role'] = $this->roleRepository->getRoleById($datePage['user']['role_id']);
            $answer['info'] = $datePage;
        }
        else
        {
            $answer['error'] = "Файла с хэшем " . $hash . " нет на сервере";
        }

        return $answer;
    } 

    public function getCommentFile($idFile)
    {
        $comment = $this->commentRepository->getCommentFile($idFile);

        return $comment;
    }

    public function addCommentFile($infoComment)
    {
        $infoFile = $this->repo->getFileByHash($infoComment['hash_file']);

        if ($infoFile == NULL)
        {
            echo("\nФайла с таким хешем нет на сервере\n");
        }
        else
        {
            $infoComment['user_id'] = $this->userRepository->getUserIdByEmail($infoComment['user_email'])['id'];
            $infoComment['file_id'] = $infoFile['id'];
    
            $idComment = $this->commentRepository->addCommentFile($infoComment);
            echo("Комментарий успешно добавлен");
        }

        return 0;
    }

    public function setScoreFile($infoScore)
    {
        $fileInfo = $this->repo->getFileByHash($infoScore['hash_file']);
        $infoScore['user_id'] = $this->userRepository->getUserIdByEmail($infoScore['user_email'])['id'];
        $infoScore['file_id'] = $fileInfo['id'];

        // Обновляем или добавляем запись в таблицу score_file об оценке файла пользователем.
        $this->scoreRepository->setScoreFile($infoScore);

        // Получить сумму оценок файла.
        $infoScore['sum_score'] = $this->scoreRepository->getSumScore($infoScore);

        // Обновляем общий рейтинг файла.
        $status = $this->repo->updateScoreFile($infoScore);

        $action = ($infoScore['value'] == 1) ? "increase raiting file" : "decrease raiting file";

        return $status;
    }

    public function setScoreUser($infoScore)
    {
        // Обновляем или добавляем запись в таблицу score_user об оценке файла пользователем.
        $this->scoreRepository->setScoreUser($infoScore);

        // Получить сумму оценок пользователя.
        $infoScore['sum_score'] = $this->scoreRepository->getSumScoreUser($infoScore);

        // Обновляем общий рейтинг пользователя.
        $status = $this->userRepository->updateScoreUser($infoScore);

        $action = ($infoScore['value'] == 1) ? "increase raiting user" : "decrease raiting user";
        
        return $status;
    }

    public function deleteComment($infoComment)
    {   
        $this->commentRepository->deleteComment($infoComment);

        return 0;
    }

    public function deleteFile($infoFile)
    {   
        $this->repo->deleteFile($infoFile);

        return 0;
    }

    public function deleteUser($infoUser)
    {   
        $this->userRepository->deleteUser($infoUser);

        return 0;
    }

    public function addLog($user, $ip, $action, $object_id)
    {
        $infoLog = array();

        $infoLog['user_id'] = $user; 
        $infoLog['ip'] = $ip; 
        $infoLog['action'] = $action; 
        $infoLog['object_id'] = $object_id; 

        $this->logger->addLog($infoLog);
    }
}