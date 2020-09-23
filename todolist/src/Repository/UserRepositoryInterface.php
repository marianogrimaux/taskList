<?php


namespace App\Repository;


use App\Entity\User;

interface UserRepositoryInterface
{
    /**
     * Find a user by Id
     *
     * @param int $id
     * @return User|null
     */
    public function findById(int $id): ?User;

    /**
     *  finds one user by criteria any of the user attributes
     * @param int $id
     * @return User|null
     */
    public function findBy(array $criteria): ?User;

    /**
     * @param User $user
     * @return bool
     */
    public function userExists(User $user): bool;

    /**
     * Create a user of not exists
     * updates user if exist
     * @param User $user
     */
    public function save(User $user): void;

}
