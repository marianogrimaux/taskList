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

    abstract protected function getTableName(): string;

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
            $statement = $this->runQuery($sql, array_values($valuesMap));
            if ($statement->rowCount() > 1) {
                $fetchedData = $statement->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $fetchedData = $statement->fetch(PDO::FETCH_ASSOC);
            }
            return $fetchedData ?: null;
        } catch (PDOException $exception) {
            throw new MapperException($exception->getMessage(), (int)$exception->getCode(), $exception);
        }
    }

    /**
     * Expects $valuesMap and $hereValues as [column => value]
     * @throws MapperException if malformed query
     */
    protected function executeUpdate(array $valuesMap, array $whereValues): void
    {
        $valuesToSet = [];
        foreach (array_keys($valuesMap) as $column) {
            $valuesToSet[] = $column .= '=:' . $column;
        }
        $whereConditions = [];
        foreach (array_keys($whereValues) as $whereValue) {
            $whereConditions[] = $whereValue .= '=:' . $whereValue;
        }
        $sql = 'UPDATE ' . $this->getTableName() . ' SET ' . implode(", ", $valuesToSet)
            . ' WHERE ' . implode('AND ', $whereConditions);
        try {
            $statement = $this->runQuery($sql, array_merge($valuesMap, $whereValues));
        } catch (PDOException $exception) {
            throw new MapperException($exception->getMessage(), (int)$exception->getCode(), $exception);
        }
    }

    /**
     * Expects $valuesMap and $hereValues as [column => value]
     * @throws MapperException if malformed query
     */
    protected function executeInsert(array $valuesMap): int
    {
        $placeholders = [];
        foreach (array_keys($valuesMap) as $column) {
            $placeholders[] = ':' . $column;
        }
        $sql = 'INSERT INTO ' . $this->getTableName() . ' ('
            . implode(', ', array_keys($valuesMap)) . ') VALUES (' . implode(', ', $placeholders) . ')';
        try {
            $statement = $this->runQuery($sql, $valuesMap);
            return (int)$this->dbConnection->lastInsertId();
        } catch (PDOException $exception) {
            throw new MapperException($exception->getMessage(), (int)$exception->getCode(), $exception);
        }
    }

    protected function executeDelete(array $valuesMap)
    {
        $whereConditions = [];
        foreach (array_keys($valuesMap) as $whereValue) {
            $whereConditions[] = $whereValue .= '=:' . $whereValue;
        }
        $sql = 'DELETE FROM ' . $this->getTableName() . ' WHERE ' . implode('AND ', $whereConditions);
        try {
            $statement = $this->runQuery($sql, $valuesMap);
        } catch (PDOException $exception) {
            throw new MapperException($exception->getMessage(), (int)$exception->getCode(), $exception);
        }
    }

    private function runQuery(string $sql, array $valuesMap) : \PDOStatement
    {
        $statement = $this->dbConnection->prepare($sql);
        $statement->execute($valuesMap);
        return $statement;
    }
 }
