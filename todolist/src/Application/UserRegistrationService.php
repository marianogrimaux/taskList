<?php

declare(strict_types=1);


namespace App\Application;


use App\Application\Dto\UserDto;
use App\Application\Exception\UserRegisterValidationException;
use App\Domain\Entity\Email;
use App\Domain\Entity\Password;
use App\Domain\Entity\User;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Service\HashingServiceInterface;

class UserRegistrationService
{
    private $hashingService;
    private $userRepository;

    public function __construct(HashingServiceInterface $hashingService, UserRepositoryInterface $userRepository)
    {
        $this->hashingService = $hashingService;
        $this->userRepository = $userRepository;
    }

    public function registerUser(string $email, string $name, string $password): UserDto
    {
        $email = $this->createEmail($email);
        $password = $this->createPassword($password);
        $user = $this->createUser($name, $email);
        $hashedPassword = $this->hashingService->hashPassword((string)$password);
        $user->setPassword(new Password($hashedPassword));
        $this->userRepository->save($user);
        return new UserDto($user->getName(), (string)$user->getEmail(), $user->getId());
    }

    private function createEmail(string $emailAddress): Email
    {
        $email = new Email($emailAddress);
        if (!$email->isValid()) {
            throw new UserRegisterValidationException('invalid email: ' . $emailAddress);
        }
        return $email;
    }

    private function createPassword(string $plainPassword): Password
    {
        $password = new Password($plainPassword);
        if (!$password->isValid()) {
            throw new UserRegisterValidationException('invalid password: '. $plainPassword);
        }
        return $password;
    }

    private function createUser(string $name, Email $email): User
    {
        $user = new User($name, $email);
        if ($this->userRepository->userExists($user)) {
            throw new UserRegisterValidationException('User exists with email:' . (string) $email);
        }
        return $user;
    }
}
