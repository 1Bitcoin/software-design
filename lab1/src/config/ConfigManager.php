<?php

class ConfigManager
{
    private $host;
    private $user;
    private $password;
    private $name;
    
    public function __construct()
    {
        $data = file_get_contents(CONFIG_DATABASE);
        $dataConfig = explode(" ", $data);

        $this->host = $dataConfig[0];
        $this->user = $dataConfig[1];
        $this->password = $dataConfig[2];
        $this->name = $dataConfig[3];
    }

    public function getHost()
    {
        return $this->host;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getName()
    {
        return $this->name;
    }
}