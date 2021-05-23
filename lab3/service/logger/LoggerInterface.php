<?php

interface LoggerInterface
{
    public function addLog($infoLog);

    public function getLogs();

}