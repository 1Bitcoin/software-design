<?php

require_once(ROOT . '/repository/score/ScoreRepositoryInterface.php');

use \RedBeanPHP\R as R;

class ScoreRepository implements ScoreRepositoryInterface
{
    private $connection;
    
    public function __construct()
    {
        
    }

    public function getSumScore($infoScore)
    {
        $file_id = $infoScore['file_id'];

        // Получаем сумму оценок всех пользователей.
        $sum = R::getAll('SELECT SUM(type_score) as total FROM `score_file` WHERE `file_id` = ?', [$file_id]);

        return $sum[0]['total'];
    }

    public function setScoreFile($infoScore)
    {
        $value = $infoScore['value'];
        $user_id = $infoScore['user_id'];
        $file_id = $infoScore['file_id'];

        // Получаем id записи с оценкой к файлу от пользователя, если она есть
        $rows = R::getAll('SELECT `id` FROM `score_file` WHERE `user_id` = ? AND `file_id` = ?', [$user_id, $file_id]);

        // Если запись уже существует - обновляем поле type_score
        // иначе - добавляем новую запись.
        if (isset($rows[0]['id']))
        {
            $idRow = $rows[0]['id'];

            $score = R::load('score_file', $idRow);
            $score->type_score = $value;
            R::store($score);
        }
        else
        {
            $score = R::dispense('score_file');

            // Заполняем объект свойствами
            $score->user_id = $user_id;
            $score->file_id = $file_id;
            $score->type_score = $value;
    
            // Сохраняем объект
            R::store($score);
        }
        
        return 0;
    }
}