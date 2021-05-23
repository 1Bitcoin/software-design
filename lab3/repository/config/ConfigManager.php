<?php

class ConfigManager
{
    private $host;
    private $password;
    private $actor;
    private $nameDatabase;
    
    public function __construct($roleID)
    {
        $data = file(CONFIG_DATABASE);

        $configActor = explode(" ", $data[$roleID]);
        $configBase = explode(" ", $data[4]);

        $user = $configActor[0];
        $password = mb_substr($configActor[1], 0, -2);
        
        $localhost = $configBase[0];
        $nameDatabase = $configBase[1];

        $this->password = $password;
        $this->actor = $user;
        
        $this->nameDatabase = $nameDatabase;
        $this->host = $localhost;

    }

    public function getActor()
    {
        return $this->actor;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getNameDatabase()
    {
        return $this->nameDatabase;
    }
}