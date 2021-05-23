<?php

require_once(ROOT . '/repository/statistics/StatisticsRepositoryInterface.php');

use \RedBeanPHP\R as R;

class StatisticsRepository implements StatisticsRepositoryInterface
{
    public function __construct()
    {

    }

    public function setUploadFilesStatistics()
    {
        $id = 1;
        $statistics = R::load('statistics', $id);
        $statistics->count_upload_files = R::count('file');;
        R::store($statistics);
        
        return 0;
    }

    public function setDownloadFilesStatistics()
    {
        $id = 1;
        $statistics = R::load('statistics', $id);
        $statistics->count_download_files++;
        R::store($statistics);

        return 0;
    }

    public function setCommentsStatistics()
    {
        $id = 1;
        $statistics = R::load('statistics', $id);
        $statistics->count_comments = R::count('comment');
        R::store($statistics);

        return 0;
    }

    public function setUsersStatistics()
    {
        $id = 1;
        $statistics = R::load('statistics', $id);
        $statistics->count_users = R::count('user', 'role_id = ?', [1]);
        $statistics->count_moderators = R::count('user', 'role_id = ?', [2]);
        $statistics->count_administrators = R::count('user', 'role_id = ?', [3]);

        R::store($statistics);

        return 0;
    }

    public function getStatistics()
    {
        $statistics = R::getAll('SELECT * FROM `statistics`');

        return $statistics;
    }
}