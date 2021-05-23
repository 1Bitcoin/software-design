<?php

interface StatisticsRepositoryInterface
{
    public function getStatistics();
    
    public function setUploadFilesStatistics();

    public function setDownloadFilesStatistics();

    public function setCommentsStatistics();
    
    public function setUsersStatistics();

}