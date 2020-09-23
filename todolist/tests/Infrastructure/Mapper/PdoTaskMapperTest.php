<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Mapper;

use App\Entity\Task;
use App\Entity\User;
use App\Infrastructure\Mapper\Task\PdoMapperTest;
use App\Infrastructure\Mapper\Task\PdoTaskMapper;
use App\Infrastructure\Mapper\User\PdoUserMapper;
use DateTime;
use PHPUnit\Framework\MockObject\MockObject;

class PdoTaskMapperTest extends PdoMapperTest
{
    public function testCreateTask(): void
    {
        $task = $this->getTask();
        $statement = $this->buildPdoStatementMockWithValueMapCheck(
            ['title', 'description', 'status', 'assigneeId', 'dueDate', 'creationDate']
        );
        $connection = $this->buildPdoMockWithQueryCheck(
            'INSERT INTO task (title, description, status, assigneeId, dueDate, creationDate) VALUES (:title, :description, :status, :assigneeId, :dueDate, :creationDate)',
            $statement
        );
        $connection->method('lastInsertId')->willReturn(1);
        $pdoTaskMapper = new PdoTaskMapper($connection, $this->getMockedUserMapper());
        $pdoTaskMapper->createTask($task);
        $this->assertEquals(1, $task->getId());
    }

    private function getTask(int $id = null): Task
    {
        $task = new Task('A test task', new DateTime(), $this->getUserMock());
        if ($id) {
            $task->setId($id);
        }
        return $task;
    }

    private function getUserMock(): MockObject
    {
        $userMock = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->getMock();
        $userMock->method('getId')->willReturn(1);
        return $userMock;
    }

    private function getMockedUserMapper()
    {
        $userMock = $this->getUserMock();
        $userMapperMock = $this->getMockBuilder(PdoUserMapper::class)
            ->disableOriginalConstructor()
            ->getMock();
        $userMapperMock->method('fetchUserBy')->willReturn($userMock);
        return $userMapperMock;
    }

    public function testUpdate(): void
    {
        $task = $this->getTask(1);
        $statement = $this->buildPdoStatementMockWithValueMapCheck(
            ['title', 'description', 'status', 'assigneeId', 'dueDate', 'creationDate', 'id']
        );
        $connection = $this->buildPdoMockWithQueryCheck(
            'UPDATE task SET title=:title, description=:description, status=:status, assigneeId=:assigneeId, dueDate=:dueDate, creationDate=:creationDate WHERE id=:id',
            $statement
        );
        $connection->method('lastInsertId')->willReturn(1);
        $pdoTaskMapper = new PdoTaskMapper($connection, $this->getMockedUserMapper());
        $pdoTaskMapper->updateTask($task);
        $this->assertEquals(1, $task->getId());
    }

    public function testDelete(): void
    {
        $task = $this->getTask(2);
        $statement = $this->buildPdoStatementMockWithValueMapCheck(['id']);
        $connection = $this->buildPdoMockWithQueryCheck('DELETE FROM task WHERE id=:id', $statement);
        $pdoTaskMapper = new PdoTaskMapper($connection, $this->getMockedUserMapper());
        $pdoTaskMapper->deleteTask($task);
    }

    public function testFetchOne(): void
    {
        $statement = $this->getMockedStatement();
        $statement->method('fetch')
            ->willReturn(
                [
                    'id' => 1,
                    'title' => 'mariano',
                    'description' => 'mariano@m.com',
                    'status' => 'asd',
                    'assigneeId' => 1,
                    'dueDate' => '2020-09-23 04:54:23',
                    'creationDate' => '2020-09-23 04:54:23'
                ]
            );
        $connection = $this->getMockedPdo($statement);
        $statement->method('rowCount')->willReturn(1);
        $pdoTaskMapper = new PdoTaskMapper($connection, $this->getMockedUserMapper());

        $task = $pdoTaskMapper->fetchTaskBy(['id' => 1]);
        $this->assertEquals(1, $task->getId());
        $this->assertEquals('mariano', $task->getTitle());
        $this->assertEquals('Pending', $task->getStatus());
        $this->assertEquals('2020-09-23 04:54:23', $task->getDuedate()->format('Y-m-d H:i:s'));
        $this->assertEquals('2020-09-23 04:54:23', $task->getCreationDate()->format('Y-m-d H:i:s'));
    }

    public function testFetchBy(): void
    {
        $statement = $this->getMockedStatement();
        $statement->method('fetch')
            ->willReturn(
                [
                    [
                        'id' => 1,
                        'title' => 'mariano',
                        'description' => 'mariano@m.com',
                        'status' => 'asd',
                        'assigneeId' => 1,
                        'dueDate' => '2020-09-23 04:54:23',
                        'creationDate' => '2020-09-23 04:54:23'
                    ],
                    [
                        'id' => 1,
                        'title' => 'mariano',
                        'description' => 'mariano@m.com',
                        'status' => 'asd',
                        'assigneeId' => 1,
                        'dueDate' => '2020-09-23 04:54:23',
                        'creationDate' => '2020-09-23 04:54:23'
                    ]
                ]
            );
        $connection = $this->getMockedPdo($statement);
        $pdoTaskMapper = new PdoTaskMapper($connection, $this->getMockedUserMapper());
        $tasks = $pdoTaskMapper->fetchBy(['assigneeId' => 1]);
        $this->assertIsArray($tasks);
        $this->assertEquals(2, count($tasks));
        foreach ($tasks as $task) {
            $this->assertEquals(1, $task->getId());
            $this->assertEquals('mariano', $task->getTitle());
            $this->assertEquals('Pending', $task->getStatus());
            $this->assertEquals('2020-09-23 04:54:23', $task->getDuedate()->format('Y-m-d H:i:s'));
            $this->assertEquals('2020-09-23 04:54:23', $task->getCreationDate()->format('Y-m-d H:i:s'));
        }
    }

}
