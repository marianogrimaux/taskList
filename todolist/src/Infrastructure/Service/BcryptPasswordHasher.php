<?php

declare(strict_types=1);


namespace App\Infrastructure\Service;


use App\Domain\Service\HashingServiceInterface;

class BcryptPasswordHasher implements HashingServiceInterface
{
    private const OPTIONS = ['cost'=> 12];

    public function hashPassword(string $password) : string
    {
        return password_hash($password, PASSWORD_BCRYPT, self::OPTIONS);
    }
}
