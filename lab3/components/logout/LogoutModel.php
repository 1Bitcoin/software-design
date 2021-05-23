<?php

require_once(COMPONENT_BASE . 'Model.php');
require_once(SERVICE_LOGGER . 'Logger.php');

class LogoutModel extends Model 
{
    public function __construct($roleID)
    {
        $this->connection = new Connection($roleID);
        $this->logger = new Logger();
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