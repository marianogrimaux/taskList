<?php

declare(strict_types=1);


namespace Tests\Infrastructure;

use App\Entity\User;
use App\Infrastructure\Mapper\MapperException;
use App\Infrastructure\Mapper\Task\PdoMapperTest;
use App\Infrastructure\Mapper\User\PdoUserMapper;

class PdoUserDatamapperTest extends PdoMapperTest
{

    public function testMappingData() : void
    {
        $statement = $this->getMockedStatement();
        $statement->method('fetch')
            ->willReturn(['id'=>1, 'name'=>'mariano', 'email'=>'mariano@m.com', 'password'=>'asd']);
        $dataMapper = new PdoUserMapper($this->getMockedPdo($statement));
        $user = $dataMapper->fetchUserBy(["id"=>1]);
        $this->assertNotNull($user);
        $this->assertEquals('mariano@m.com', $user->getEmail());
        $this->assertEquals('mariano', $user->getName());
        $this->assertEquals(1, $user->getId());
    }

    public function testNoResults() : void
    {
        $statement = $this->getMockedStatement();
        $statement->method('fetch')
            ->willReturn(false);
        $dataMapper = new PdoUserMapper($this->getMockedPdo($statement));
        $user = $dataMapper->fetchUserBy(["id"=>1]);
        $this->assertEquals(null, $user);
    }

    /**
     * Test updating users:
     * Validation in parameters map.
     * Validation in query that will be executed.
     */
    public function testUpdateUser() : void
    {
        $user = new User("Mariano", "email@email.com");
        $user->setPassword("pass");
        $user->setId(1);
        $statement = $this->buildPdoStatementMockWithValueMapCheck(['name', 'email', 'password']);
        $connection = $this->buildPdoMockWithQueryCheck(
            'UPDATE user SET name=:name, email=:email, password=:password WHERE id=:id',
            $statement
        );
        $dataMapper = new PdoUserMapper($connection);
        $dataMapper->updateUser($user);
    }

    /**
     * Test create users
     * Check query and setting id to user
     */
    public  function testCrateUser() : void
    {
        $user = new User("Mariano", "email@email.com");
        $user->setPassword("pass");
        $statement = $this->buildPdoStatementMockWithValueMapCheck(['name', 'email', 'password']);
        $connection = $this->buildPdoMockWithQueryCheck(
            'INSERT INTO user (name, email, password) VALUES (:name, :email, :password)',
            $statement
        );
        $connection->method('lastInsertId')->willReturn(1);
        $dataMapper = new PdoUserMapper($connection);
        $dataMapper->createUser($user);
        $this->assertEquals(1, $user->getId());
    }

    /**
     * Test PDO exceptions handling
     * Maintain exception wrapping
     */
    public function testPdoExceptionHandlingOnInsert() {
        $user = new User("Mariano", "email@email.com");
        $user->setPassword("pass");
        $connection = $this->getMockedPdo();
        $connection->method('prepare')
            ->willThrowException(new \PDOException());
        $dataMapper = new PdoUserMapper($connection);
        $this->expectException(MapperException::class);
        $dataMapper->createUser($user);
        $this->expectException(MapperException::class);
        $dataMapper->updateUser($user);

    }

     /**
      * Test PDO exceptions handling
      * Maintain exception wrapping
      */
    public function testPdoSelectException() : void
    {
        $connection = $this->getMockedPdo();
        $connection->method('prepare')
            ->willThrowException(new \PDOException());
        $dataMapper = new PdoUserMapper($connection);
        $this->expectException(MapperException::class);
        $dataMapper->fetchUserBy(['id'=>1]);
    }
}
