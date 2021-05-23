<?php

require_once(ROOT . '/repository/file/FileRepositoryInterface.php');

use \RedBeanPHP\R as R;

class FileRepository implements FileRepositoryInterface
{
    public function __construct()
    {

    }

    public function getCountRows()
    {
        $countFiles = R::count('file');

        return $countFiles;
    }

    public function updateScoreFile($infoScore)
    {
        $file_id = $infoScore['file_id'];
        $sumScore = $infoScore['sum_score'];

        $file = R::load('file', $file_id);
        $file->raiting = $sumScore;
        R::store($file);
        
        return 0;
    }

    public function getFileByHash($hash)
    {
        $answer = NULL;
        $file = R::getAll('SELECT * FROM `file` WHERE `hash` = ?', [$hash]);

        if (isset($file[0]))
            $answer = $file[0];
        
        return $answer;
    }

    public function addFile($infoFile)
    {
        $name = $infoFile['name'];
        $hash = $infoFile['hash'];
        $type = $infoFile['type'];
        $size = $infoFile['size'];
        $user_id = $infoFile['user_id'];

        $file = R::dispense('file');

        // Заполняем объект свойствами
        $file->name = $name;
        $file->hash = $hash;
        $file->type = $type;
        $file->size = $size;
        $file->user_id = $user_id;

        // Сохраняем объект
        R::store($file); 
        
        return $file->id;
    }

    public function getRowsByLimit($start, $end)
    {
        $files = R::getAll('SELECT * FROM `file` LIMIT ?, ?', [$start, $end]);

        return $files;
    }

    public function deleteFile($infoFile)
    {
        $idFile = $infoFile['file_id'];
        R::trashBatch('file', [$idFile]);

        return 0;
    }
}