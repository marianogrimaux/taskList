<?php

declare(strict_types=1);

namespace App\Infrastructure\Mapper\User;

use App\Entity\User;
use App\Infrastructure\Mapper\MapperException;
use App\Infrastructure\Mapper\PdoDataMapper;

class PdoUserMapper extends PdoDataMapper implements UserMapperInterface
{
    const TABLE = 'user';

    /*
     * Fecth user where values are equal
     * values map expects [column => value].
     * @throws MapperException if malformed query
     */
    public function fetchUserBy(array $valuesMap): ?User
    {
        $sql = 'SELECT * FROM ' . self::TABLE . ' WHERE ';
        $columns = [];
        if (count($valuesMap) > 1) {
            foreach (array_keys($valuesMap) as $column) {
                $columns[] = $column . '=?';
            }
            $sql .= implode(' AND ', $columns);
        } else {
            $sql.= array_keys($valuesMap)[0] . '=?';
        }
        try {
        $statement = $this->dbConnection->prepare($sql);
            $statement->execute(array_values($valuesMap));
            $userValues = $statement->fetch(\PDO::FETCH_ASSOC);
            return $userValues ? $this->buildUserFromDbValues($userValues) : null;
        } catch (\PDOException $exception) {
            throw new MapperException($exception->getMessage(), (int)$exception->getCode(), $exception);
        }
    }


    /*
     * Fecth user where values are equal
     * values map expects [column => value].
     * @throws MapperException if malformed query
     */
    public function updateUser(User $user): void
    {
        $data = [
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword()
        ];
        $sql = 'UPDATE ' . self::TABLE . ' SET name=:name, email=:email, password=:password WHERE id=:id';
        try{
            $stmt= $this->dbConnection->prepare($sql);
            $stmt->execute($data);
        } catch (\PDOException $exception) {
            throw new MapperException($exception->getMessage(),(int)$exception->getCode(), $exception);
        }
    }


    /*
     * Fecth user where values are equal
     * values map expects [column => value].
     * @throws MapperException if malformed query
     */
    public function createUser(User $user): void
    {
        $data = [
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword()
        ];
        $sql = 'INSERT INTO ' . self::TABLE . ' (name, email, password) VALUES (:name, :email, :password)';
        try{
            $statement =  $this->dbConnection->prepare($sql);
            $statement->execute($data);
            $user->setId((int) $this->dbConnection->lastInsertId());
        } catch (\PDOException $exception) {
            throw new MapperException($exception->getMessage(),(int)$exception->getCode(), $exception);
        }
    }

    private function buildUserFromDbValues(array $userValues) {
        $user = new User($userValues['name'], $userValues['email']);
        $user->setPassword($userValues['password']);
        $user->setId($userValues['id']);
        return $user;
    }

}
