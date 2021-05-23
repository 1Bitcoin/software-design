<?php

require_once(ROOT . '/repository/config/ConfigManager.php');
require_once(ROOT . '/vendor/autoload.php');

use \RedBeanPHP\R as R;

class Connection
{
    public function __construct($roleID)
    {
        $configManager = new ConfigManager($roleID);

        $host = $configManager->getHost();
        $password = $configManager->getPassword();
        $nameDatabase = $configManager->getNameDatabase();
        $actor = $configManager->getActor();

        // Red Bean имеет поддержку многих СУБД, например PostgreSQL
        //R::setup('pgsql:host=' . $host . ';dbname=' . $nameDatabase, $actor, $password);
        R::setup('mysql:host=' . $host . ';dbname=' . $nameDatabase, $actor, $password);
 
        if (!R::testConnection()) 
        {
            print_r('No DB connection!');
            $this->reconnection();
        }
    }

    public function __destruct() 
    {
        R::close();
    }

    public function reconnection()
    {
        $countTry = 5;
        $connected = FALSE;

        while ($countTry && !$connected)
        {
            sleep(5);
            if (R::testConnection()) 
            {
                print_r('Connected!');
                $connected = TRUE;
            }

            $countTry--;
        }
    }
}