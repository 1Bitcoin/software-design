<?php

require_once(COMPONENT_BASE . 'Model.php');
require_once(CONNECTION . 'Connection.php');
require_once(SERVICE_LOGGER . 'Logger.php');
require_once(SERVICE_STATISTICS . 'Statistics.php');

class FileModel extends Model 
{
    protected $connection;
    protected $userRepository;
    protected $roleRepository;
    protected $scoreRepository;
    protected $commentRepository;

    public function __construct(FileRepository $fileRepository, UserRepository $userRepository, 
                                RoleRepository $roleRepository, CommentRepository $commentRepository, 
                                ScoreRepository $scoreRepository, $roleID) 
    {
        $this->connection = new Connection($roleID);
        
        $this->repo = $fileRepository;
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
        $this->commentRepository = $commentRepository;
        $this->scoreRepository = $scoreRepository;

        $this->logger = new Logger();
        $this->statistics = new Statistics();
    }

    public function filesPagination($limit, $page)
    {
        // Проверить номер страницы
        if (!isset($page)) 
            $page = 1;  

        $page_first_result = ($page - 1) * $limit; 
        $table = 'files';

        // Формируем данные для передачи в html форму
        $result['title'] = "Список файлов";
        $result['files'] = $this->repo->getRowsByLimit($page_first_result, $limit);    
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
        $fileInfo = $this->repo->getFileByHash($infoComment['hash_file']);
        $infoComment['user_id'] = $this->userRepository->getUserIdByEmail($infoComment['user_email'])['id'];
        $infoComment['file_id'] = $fileInfo['id'];

        $idComment = $this->commentRepository->addCommentFile($infoComment);

        $this->addLog($infoComment['user_id'], $infoComment['ip'], "add comment", $idComment);
        $this->statistics->setCommentsStatistics();

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

        $this->addLog($infoScore['user_id'], $infoScore['ip'], $action, $infoScore['file_id']);
        
        return $status;
    }

    public function setScoreUser($infoScore)
    {
        $fileInfo = $this->repo->getFileByHash($infoScore['hash_file']);
        $infoScore['user_id_received'] = $fileInfo['user_id'];

        // Обновляем или добавляем запись в таблицу score_user об оценке файла пользователем.
        $this->scoreRepository->setScoreUser($infoScore);

        // Получить сумму оценок пользователя.
        $infoScore['sum_score'] = $this->scoreRepository->getSumScoreUser($infoScore);

        // Обновляем общий рейтинг пользователя.
        $status = $this->userRepository->updateScoreUser($infoScore);

        $action = ($infoScore['value'] == 1) ? "increase raiting user" : "decrease raiting user";

        $this->addLog($infoScore['user_id'], $infoScore['ip'], $action, $infoScore['user_id_received']);
        
        return $status;
    }

    public function deleteComment($infoComment)
    {   
        $answer = array();

        // Есть пользователь является модератором или администратором.
        if ($infoComment['role_id'] > 1)
        {
            $this->commentRepository->deleteComment($infoComment);
            $this->addLog($infoComment['user_id'], $infoComment['ip'], "delete comment", $infoComment['comment_id']);
            $this->statistics->setCommentsStatistics();
        }
        else
        {
            $answer['error'] = 'Недостаточно прав для удаления комментария!';
        }

        return $answer;
    }

    public function deleteFile($infoFile)
    {   
        $answer = array();

        // Есть пользователь является модератором или администратором.
        if ($infoFile['role_id'] > 1)
        {
            $this->repo->deleteFile($infoFile);
            
            $this->addLog($infoFile['user_id'], $infoFile['ip'], "delete file", $infoFile['file_id']);
            $this->statistics->setUploadFilesStatistics();
            $this->statistics->setCommentsStatistics();
        }
        else
        {
            $answer['error'] = 'Недостаточно прав для удаления файла!';
        }

        return $answer;
    }

    public function deleteUser($infoUser)
    {   
        $answer = array();

        // Есть пользователь является модератором или администратором.
        if ($infoUser['role_id'] > 2)
        {
            $this->userRepository->deleteUser($infoUser);

            $this->addLog($infoUser['user_id'], $infoUser['ip'], "delete user", $infoUser['delete_user_id']);
            $this->statistics->setUsersStatistics();
            $this->statistics->setUploadFilesStatistics();
            $this->statistics->setCommentsStatistics();
        }
        else
        {
            $answer['error'] = 'Недостаточно прав для удаления файла!';
        }

        return $answer;
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