<?php

declare(strict_types=1);

namespace App\Infrastructure\Mapper;

abstract class PdoDataMapper
{
    protected $dbConnection;

    function __construct(\PDO $dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }
}
