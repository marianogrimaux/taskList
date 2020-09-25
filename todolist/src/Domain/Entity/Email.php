<?php

declare(strict_types=1);


namespace App\Domain\Entity;

use App\Domain\Entity\Exception\InvalidEmailException;

class Email
{
    private $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    public function isValid() : bool
    {
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        return true;
    }

    public function __toString() : string
    {
        return $this->email;
    }

    public function equals(Email $other) : bool
    {
        return $this->email === $other->__toString();
    }
}
