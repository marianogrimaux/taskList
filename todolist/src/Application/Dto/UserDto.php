<?php

declare(strict_types=1);


namespace App\Application\Dto;


class UserDto
{
    private $name;
    private $email;
    private $id;

    public function __construct(string $name, string $email, int $id)
    {
        $this->name = $name;
        $this->email = $email;
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}
