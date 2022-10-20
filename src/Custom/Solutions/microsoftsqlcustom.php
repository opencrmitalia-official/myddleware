<?php

namespace App\Custom\Solutions;

use App\Solutions\microsoftsql;

class microsoftsqlcustom extends microsoftsql
{
    protected function generatePdo()
    {
        $newPdo = null;

        $this->set_driver();
        if ('sqlsrv' == $this->driver) {
            $newPdo = new \PDO($this->driver . ':Server=' . $this->paramConnexion['host'] . ',' . $this->paramConnexion['port'] . ';Database=' . $this->paramConnexion['database_name'], $this->paramConnexion['login'], $this->paramConnexion['password']);
        } else {
            $newPdo = new \PDO($this->driver . ':host=' . $this->paramConnexion['host'] . ';port=' . $this->paramConnexion['port'] . ';dbname=' . $this->paramConnexion['database_name'] . ';charset=' . $this->charset, $this->paramConnexion['login'], $this->paramConnexion['password']);
        }

        $newPdo->query('SET ANSI_NULLS ON;');
        $newPdo->query('SET ANSI_WARNINGS ON;');

        return $newPdo;
    }
}
