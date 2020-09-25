<?php

declare(strict_types=1);


namespace Tests\Application;

use App\Application\Dto\UserDto;
use App\Application\Exception\UserRegisterValidationException;
use App\Application\UserRegistrationService;
use App\Domain\Entity\User;
use App\Infrastructure\Repository\UserRepository;
use App\Infrastructure\Service\BcryptPasswordHasher;
use PHPUnit\Framework\TestCase;

class UserRegistrationServiceTest extends TestCase
{
    private $userRepository;
    private $hashedSerivce;

    public function testCreateUser(): void
    {
        $email = 'mariano@gmail.com';
        $name = 'Mariano';
        $password = '12345678';
        $this->userRepository->expects($this->once())
            ->method('userExists')
            ->willReturn(false);
        $this->userRepository->expects($this->once())
            ->method('save')
            ->willReturnCallback(
                function (User $user) use ($password) {
                    $this->assertNotEquals($user->getPassword()->__toString(), $password);
                    $user->setId(1);
                }
            );
        $service = new UserRegistrationService($this->hashedSerivce, $this->userRepository);
        $output = $service->registerUser($email, $name, $password);
        $this->assertInstanceOf(UserDto::class, $output);
        $this->assertNotNull($output->getId());
        $this->assertEquals($email, $output->getEmail());
        $this->assertNotNull($name, $output->getName());
    }

    public function testInvalidEmail(): void
    {
        $email = 'mariano@.com';
        $name = 'Mariano';
        $password = '12345678';
        $this->userRepository->expects($this->never())
            ->method('userExists');
        $this->userRepository->expects($this->never())
            ->method('save');
        $service = new UserRegistrationService($this->hashedSerivce, $this->userRepository);
        $this->expectException(UserRegisterValidationException::class);
        $service->registerUser($email, $name, $password);
    }

    public function testInvalidPassword(): void
    {
        $email = 'mariano@gmail.com';
        $name = 'Mariano';
        $password = 'sd';
        $this->userRepository->expects($this->never())
            ->method('userExists');
        $this->userRepository->expects($this->never())
            ->method('save');
        $service = new UserRegistrationService($this->hashedSerivce, $this->userRepository);
        $this->expectException(UserRegisterValidationException::class);
        $service->registerUser($email, $name, $password);
    }

    public function testUserExists(): void
    {
        $email = 'mariano@gmail.com';
        $name = 'Mariano';
        $password = '12345678';
        $this->userRepository->expects($this->once())
            ->method('userExists')
            ->willReturn(true);
        $this->userRepository->expects($this->never())
            ->method('save');
        $service = new UserRegistrationService($this->hashedSerivce, $this->userRepository);
        $this->expectException(UserRegisterValidationException::class);
        $service->registerUser($email, $name, $password);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->hashedSerivce = new BcryptPasswordHasher();
    }
}
