<?php

declare(strict_types=1);


namespace App\Infrastructure\Mapper\User;


use App\Entity\User;

interface UserMapperInterface
{
    public function fetchUserBy(array $valuesMap) : ?User;

    public function saveUser(User $user) : void;

    public function createUser(User $user) : void;

}
