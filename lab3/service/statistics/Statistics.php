<?php

require_once(SERVICE_STATISTICS . 'StatisticsInterface.php');
require_once(REPOSITORY . 'statistics/StatisticsRepository.php');

class Statistics implements StatisticsInterface
{
    protected $statisticsRepository;

    public function __construct()
    {
        $this->statisticsRepository = new StatisticsRepository();
    }

    public function setUploadFilesStatistics()
    {
        $this->statisticsRepository->setUploadFilesStatistics();

        return 0;
    }

    public function setDownloadFilesStatistics()
    {
        $this->statisticsRepository->setDownloadFilesStatistics();

        return 0;
    }

    public function setCommentsStatistics()
    {
        $this->statisticsRepository->setCommentsStatistics();

        return 0;
    }

    public function setUsersStatistics()
    {
        $this->statisticsRepository->setUsersStatistics();

        return 0;
    }

    public function getStatistics()
    {
        $statistics = $this->statisticsRepository->getStatistics();

        return $statistics;
    }

}








