<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Repository;

use App\Domain\Entity\Email;
use App\Domain\Entity\User;
use App\Infrastructure\Mapper\User\PdoUserMapper;
use App\Infrastructure\Repository\UserRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UserRepositoryTest extends TestCase
{
    public function testCreateUser()
    {
        $user = new User("mariano", new Email("email@email.com"));
        $mapper = $this->getMockedMapper();
        $mapper->expects($this->once())
            ->method('createUser')
            ->with($user)
            ->willReturnCallback(
                function ($user) {
                    $user->setId(1);
                }
            );
        $mapper->expects($this->never())
            ->method('updateUser');
        $userRepo = new UserRepository($mapper);
        $userRepo->save($user);
        $this->assertNotNull($user->getId());
        $this->assertEquals(1, $user->getId());
    }

    protected function getMockedMapper(): MockObject
    {
        $mockedMapper = $this->getMockBuilder(PdoUserMapper::class)
            ->disableOriginalConstructor()
            ->getMock();
        return $mockedMapper;
    }

    public function testUpdateUser()
    {
        $user = new User("mariano", new Email("email@email.com"));
        $user->setId(22);
        $mapper = $this->getMockedMapper();
        $mapper->expects($this->once())
            ->method('updateUser')
            ->with($user)
            ->willReturnCallback(
                function ($user) {
                    $this->assertEquals(22, $user->getId());
                }
            );
        $mapper->expects($this->never())
            ->method('createUser');
        $userRepo = new UserRepository($mapper);
        $userRepo->save($user);
        $this->assertNotNull($user->getId());
        $this->assertEquals(22, $user->getId());
    }

    public function testFindBy()
    {
        $user = new User("mariano", new Email("email@email.com"));
        $searchParams = ['email' => 'mariano'];
        $mapper = $this->getMockedMapper();
        $mapper->expects($this->once())
            ->method('fetchUserBy')
            ->with($searchParams)
            ->willReturn($user);
        $userRepo = new UserRepository($mapper);
        $user = $userRepo->findBy($searchParams);
        $this->assertNotNull($user);
    }

    public function testFindByNoResults()
    {
        $user = new User("mariano", new Email("email@email.com"));
        $searchParams = ['email' => 'mariano'];
        $mapper = $this->getMockedMapper();
        $mapper->expects($this->once())
            ->method('fetchUserBy')
            ->with($searchParams)
            ->willReturn(null);
        $userRepo = new UserRepository($mapper);
        $user = $userRepo->findBy($searchParams);
        $this->assertNull($user);
    }

    public function testUserExists()
    {
        $user = new User("mariano", new Email("email@email.com"));
        $mapper = $this->getMockedMapper();
        $mapper->expects($this->once())
            ->method('fetchUserBy')
            ->with(['email' => $user->getEmail()])
            ->willReturn($user);
        $userRepo = new UserRepository($mapper);
        $exists = $userRepo->userExists($user);
        $this->assertEquals(true, $exists);
    }

    public function testUserDoesNotExist()
    {
        $user = new User("mariano", new Email("email@email.com"));
        $mapper = $this->getMockedMapper();
        $mapper->expects($this->once())
            ->method('fetchUserBy')
            ->with(['email' => $user->getEmail()])
            ->willReturn(null);
        $userRepo = new UserRepository($mapper);
        $exists = $userRepo->userExists($user);
        $this->assertEquals(false, $exists);
    }
}
