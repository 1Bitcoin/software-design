<?php

interface LoggerRepositoryInterface
{
    public function addLog($infoLog);

    public function getLogs();

}