<?php

declare(strict_types=1);

namespace App\Infrastructure\Mapper;

use PDO;
use PDOException;

abstract class PdoDataMapper
{
    protected $dbConnection;

    function __construct(PDO $dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    protected function executeSelect(array $valuesMap): ?array
    {
        $sql = 'SELECT * FROM ' . $this->getTableName() . ' WHERE ';
        $columns = [];
        if (count($valuesMap) > 1) {
            foreach (array_keys($valuesMap) as $column) {
                $columns[] = $column . '=?';
            }
            $sql .= implode(' AND ', $columns);
        } else {
            $sql .= array_keys($valuesMap)[0] . '=?';
        }
        try {
            $statement = $this->dbConnection->prepare($sql);
            $statement->execute(array_values($valuesMap));
            $fetchedData = $statement->fetch(PDO::FETCH_ASSOC);
            return $fetchedData ?: null;
        } catch (PDOException $exception) {
            throw new MapperException($exception->getMessage(), (int)$exception->getCode(), $exception);
        }
    }

    abstract protected function getTableName(): string;
}
