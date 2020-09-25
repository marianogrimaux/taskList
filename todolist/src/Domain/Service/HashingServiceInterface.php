<?php

declare(strict_types=1);


namespace App\Domain\Service;


interface HashingServiceInterface
{
    public function hashPassword(string $password) :string;

}
