<?php

declare(strict_types=1);


namespace Tests\Infrastructure;

use App\Entity\User;
use App\Infrastructure\Mapper\MapperException;
use App\Infrastructure\Mapper\User\PdoUserMapper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UserDatamapperTest extends TestCase
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

    /*
     * Test updating users:
     * Validation in parameters map.
     * Validation in query that will be executed.
     */
    public function testUpdateUser() : void
    {
        $user = new User("Mariano", "email@email.com");
        $user->setPassword("pass");
        $user->setId(1);
        $connection = $this->getMockedPdo();
        $connection->expects($this->once())
            ->method('prepare')
            ->will($this->returnCallback(
                function ($query) {
                    $this->assertEquals(
                        'UPDATE user SET name=:name, email=:email, password=:password WHERE id=:id',
                        $query
                    );
                    $statement = $this->getMockedStatement();
                    $statement->expects($this->once())
                        ->method('execute')
                        ->willReturn($this->returnCallback(
                            function (array $values) {
                                $this->assertTrue(array_key_exists('name', $values));
                                $this->assertTrue(array_key_exists('email', $values));
                                $this->assertTrue(array_key_exists('password', $values));
                            }
                        ));
                    return $statement;
                }
            ))
        ;
        $dataMapper = new PdoUserMapper($connection);
        $dataMapper->updateUser($user);
    }

    /*
     * Test create users
     * Check query and setting id to user
     */
    public  function testCrateUser() : void
    {
        $user = new User("Mariano", "email@email.com");
        $user->setPassword("pass");
        $connection = $this->getMockedPdo();
        $connection->expects($this->once())
            ->method('prepare')
            ->will($this->returnCallback(
                function ($query) {
                    $this->assertEquals(
                        'INSERT INTO user (name, email, password) VALUES (:name, :email, :password)',
                        $query
                    );
                    $statement = $this->getMockedStatement();
                    $statement->expects($this->once())
                        ->method('execute')
                        ->willReturn($this->returnCallback(
                            function (array $values) {
                                $this->assertTrue(array_key_exists('name', $values));
                                $this->assertTrue(array_key_exists('email', $values));
                                $this->assertTrue(array_key_exists('password', $values));
                                return true;
                            }
                        ));
                    return $statement;
                }
            ));
        $connection->method('lastInsertId')
        ->willReturn(1);
        $dataMapper = new PdoUserMapper($connection);
        $dataMapper->createUser($user);
        $this->assertEquals(1, $user->getId());
    }

    /*
     *
     */
    public function testPdoExceptionHandlingOnInsert() {
        $user = new User("Mariano", "email@email.com");
        $user->setPassword("pass");
        $connection = $this->getMockedPdo();
        $connection->method('prepare')
            ->willThrowException(new MapperException());
        $dataMapper = new PdoUserMapper($connection);
        $this->expectException(MapperException::class);
        $dataMapper->createUser($user);
        $this->expectException(MapperException::class);
        $dataMapper->updateUser($user);

    }

    public function testPdoSelectException() : void
    {
        $connection = $this->getMockedPdo();
        $connection->method('prepare')
            ->willThrowException(new \PDOException());
        $dataMapper = new PdoUserMapper($connection);
        $this->expectException(MapperException::class);
        $dataMapper->fetchUserBy(['id'=>1]);
    }

    private function getMockedStatement() : MockObject
    {
        $statement = $this->getMockBuilder('\PDOStatement')
            ->disableOriginalConstructor()
            ->getMock();
        return $statement;
    }

    private function getMockedPdo(MockObject $statement = null) : MockObject
    {
        $db = $this->getMockBuilder('\PDO')
            ->disableOriginalConstructor()
            ->getMock();
        if ($statement) {
            $db->method('prepare')->willReturn($statement);
        }
        return $db;
    }
}
