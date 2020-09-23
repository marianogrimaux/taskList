<?php

declare(strict_types=1);

namespace App\Infrastructure\Mapper\User;

use App\Entity\User;
use App\Infrastructure\Mapper\PdoDataMapper;

class PdoUserMapper extends PdoDataMapper implements UserMapperInterface
{
    public function fetchUserBy(array $valuesMap): ?User
    {
        $userValues = $this->executeSelect($valuesMap);
        return $userValues ? $this->buildUserFromDbValues($userValues) : null;
    }

    /*
     * Fecth user where values are equal
     * values map expects [column => value].
     * @param array $userValues
     * @throws MapperException if malformed query
     */
    private function buildUserFromDbValues(array $userValues)
    {
        $user = new User($userValues['name'], $userValues['email']);
        $user->setPassword($userValues['password']);
        $user->setId($userValues['id']);
        return $user;
    }

    /**
     * @param User
     */

    public function updateUser(User $user): void
    {
        $valuesMap = [
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword()
        ];
        $this->executeUpdate($valuesMap, ['id' => $user->getId()]);
    }


    /**
     * @param User
     */
    public function createUser(User $user): void
    {
        $data = [
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword()
        ];
        $userId = $this->executeInsert($data);
        $user->setId($userId);
    }

    protected function getTableName(): string
    {
        return 'user';
    }
}
