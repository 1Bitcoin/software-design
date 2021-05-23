<?php

require_once(COMPONENT_BASE . 'Model.php');
require_once(CONNECTION . 'Connection.php');
require_once(SERVICE_STATISTICS . 'Statistics.php');

class IndexModel extends Model 
{
    protected $connection;

    public function __construct(StatisticsRepository $statisticsRepository, $roleID) 
    {
        $this->connection = new Connection($roleID);
        $this->repo = $statisticsRepository;

        $this->statistics = new Statistics();
    }
    
    public function getStatistics()
    {
        $statistics = $this->statistics->getStatistics();

        return $statistics;
    }
}