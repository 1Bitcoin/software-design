<?php

require_once(COMPONENT_BASE . 'Model.php');
require_once(SERVICE_LOGGER . 'Logger.php');

class LoggingModel extends Model 
{
    public function __construct($roleID)
    {
        $this->connection = new Connection($roleID);
        $this->logger = new Logger();
    }

    public function getLogs()
    {
        $logs = $this->logger->getLogs();

        return $logs;
    }
}