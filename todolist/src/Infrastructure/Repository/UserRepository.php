<?php

declare(strict_types=1);


namespace App\Infrastructure\Repository;

use App\Domain\Entity\User;
use App\Domain\Repository\UserRepositoryInterface;
use App\Infrastructure\Mapper\User\UserMapperInterface;

class UserRepository implements UserRepositoryInterface
{
    private $mapper;

    function __construct(UserMapperInterface $mapper)
    {
        $this->mapper = $mapper;
    }

    public function findById(int $id): ?User
    {
        return $this->mapper->fetchUserBy(['id' => $id]);
    }

    public function findBy(array $criteria): ?User
    {
        return $this->mapper->fetchUserBy($criteria);
    }

    public function userExists(User $user): bool
    {
        return !!$this->mapper->fetchUserBy(['email' => $user->getEmail()]);
    }

    public function save(User $user): void
    {
        if ($user->getId()) {
            $this->mapper->updateUser($user);
        } else {
            $this->mapper->createUser($user);
        }
    }
}
