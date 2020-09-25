<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Entity\Email;

class User
{
    private $id;
    private $email;
    private $password;
    private $name;

    function __construct(string $name,  Email $email)
    {
        $this->name = $name;
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getEmail(): Email
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(Email $email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPassword(): ?Password
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword(Password $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
