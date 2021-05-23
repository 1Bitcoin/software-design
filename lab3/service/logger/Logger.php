<?php

require_once(SERVICE_LOGGER . 'LoggerInterface.php');
require_once(REPOSITORY . 'logger/LoggerRepository.php');

class Logger implements LoggerInterface
{
    protected $loggerRepository;

    public function __construct()
    {
        $this->loggerRepository = new LoggerRepository();
    }

    public function addLog($infoLog)
    {
        $this->loggerRepository->addLog($infoLog);
    }

    public function getLogs()
    {
        $logs = $this->loggerRepository->getLogs();

        return $logs;
    }

}