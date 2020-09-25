<?php

declare(strict_types=1);


namespace App\Domain\Entity;


use App\Domain\Entity\Exception\InvalidPasswordException;

class Password
{
    private const PASSWORD_LENGHT = 8;
    private $password;

    public function __construct(string  $password)
    {
        $this->password = $password;
    }

   public function isValid() : bool
   {
        if ( strlen($this->password) < self::PASSWORD_LENGHT)
        {
            return false;
        }
        return true;
   }

   public function __toString() : string
   {
        return $this->password;
   }

   public function equals(Password $other) : bool
   {
        return $this->password == $other->__toString();
   }
}
