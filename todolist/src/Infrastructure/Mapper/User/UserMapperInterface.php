<?php

declare(strict_types=1);


namespace App\Infrastructure\Mapper\User;

use App\Domain\Entity\User;

interface UserMapperInterface
{
    public function fetchUserBy(array $valuesMap): ?User;

    public function updateUser(User $user): void;

    public function createUser(User $user): void;
}
