<?php

declare(strict_types=1);

namespace tests\Repository;

use App\Entity\Task;
use App\Entity\User;
use App\Infrastructure\Mapper\Task\PdoTaskMapper;
use App\Repository\TaskRepository;
use DateTime;
use PHPUnit\Framework\testCase;

class TaskRepositorytest extends testCase
{

    public function testCreateTask(): void
    {
        $task = new Task("task", new DateTime(), $this->getMockedUser());
        $mapper = $this->getMockedMapper();
        $mapper->expects($this->once())
            ->method('createTask')
            ->with($task)
            ->willReturnCallback(
                function ($task) {
                    $task->setId(1);
                }
            );
        $mapper->expects($this->never())
            ->method('updateTask');
        $taskRepo = new TaskRepository($mapper);
        $taskRepo->save($task);
        $this->assertNotNull($task->getId());
        $this->assertEquals(1, $task->getId());
    }

    private function getMockedUser()
    {
        $mockedUser = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockedUser->method('getId')
            ->willReturn(1);
        return $mockedUser;
    }

    private function getMockedMapper()
    {
        $mockedMapper = $this->getMockBuilder(PdoTaskMapper::class)
            ->disableOriginalConstructor()
            ->getMock();
        return $mockedMapper;
    }

    public function testUpdateTask(): void
    {
        $task = new Task("task", new DateTime(), $this->getMockedUser());
        $task->setId(22);
        $mapper = $this->getMockedMapper();
        $mapper->expects($this->once())
            ->method('updateTask')
            ->with($task);
        $mapper->expects($this->never())
            ->method('createTask');
        $taskRepo = new TaskRepository($mapper);
        $taskRepo->save($task);
    }

    public function testFindById(): void
    {
        $task = new Task("task", new DateTime(), $this->getMockedUser());
        $idToSearch = 22;
        $task->setId($idToSearch);
        $searchParams = ['id' => $idToSearch];
        $mapper = $this->getMockedMapper();
        $mapper->expects($this->once())
            ->method('fetchTaskBy')
            ->with($searchParams)
            ->willReturn($task);
        $mapper->expects($this->never())
            ->method('fetchBy');
        $taskRepo = new TaskRepository($mapper);
        $taskResult = $taskRepo->findById($idToSearch);
        $this->assertIsNotArray($taskResult);
        $this->assertInstanceOf(Task::class, $taskResult);
    }

    public function testDeleteTask(): void
    {
        $task = new Task("task", new DateTime(), $this->getMockedUser());
        $idToDelete = 22;
        $task->setId($idToDelete);
        $mapper = $this->getMockedMapper();
        $mapper->expects($this->once())
            ->method('deleteTask')
            ->with($task);
        $taskRepo = new TaskRepository($mapper);
        $taskRepo->delete($task);
    }
}
