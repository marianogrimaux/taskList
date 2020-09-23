<?php

declare(strict_types=1);

namespace App\Infrastructure\Mapper\User;

use App\Entity\User;
use App\Infrastructure\Mapper\MapperException;
use App\Infrastructure\Mapper\PdoDataMapper;
use PDOException;

class PdoUserMapper extends PdoDataMapper implements UserMapperInterface
{
    const TABLE = 'user';

    public function fetchUserBy(array $valuesMap): ?User
    {
        $userValues = $this->executeSelect($valuesMap);
        return $userValues ? $this->buildUserFromDbValues($userValues) : null;
    }

    /*
     * Fecth user where values are equal
     * values map expects [column => value].
     * @throws MapperException if malformed query
     */

    private function buildUserFromDbValues(array $userValues)
    {
        $user = new User($userValues['name'], $userValues['email']);
        $user->setPassword($userValues['password']);
        $user->setId($userValues['id']);
        return $user;
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
        try {
            $stmt = $this->dbConnection->prepare($sql);
            $stmt->execute($data);
        } catch (PDOException $exception) {
            throw new MapperException($exception->getMessage(), (int)$exception->getCode(), $exception);
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
        try {
            $statement = $this->dbConnection->prepare($sql);
            $statement->execute($data);
            $user->setId((int)$this->dbConnection->lastInsertId());
        } catch (PDOException $exception) {
            throw new MapperException($exception->getMessage(), (int)$exception->getCode(), $exception);
        }
    }

    protected function getTableName(): string
    {
        return 'user';
    }
}
