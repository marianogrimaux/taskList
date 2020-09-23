<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Task;
use App\Infrastructure\Mapper\Task\TaskMapperInterface;

class TaskRepository implements TaskRepositoryInterface
{
    private $mapper;

    function __construct(TaskMapperInterface $mapper)
    {
        $this->mapper = $mapper;
    }

    public function findById(int $id): Task
    {
        return $this->mapper->fetchTaskBy(['id'=> $id]);
    }

    public function save(Task $task): void
    {
        if ($task->getId()) {
            $this->mapper->updateTask($task);
        } else {
            $this->mapper->createTask($task);
        }
    }

    public function delete(Task $task): void
    {
        $this->mapper->deleteTask($task);
    }

    public function findBy(array $criteria): array
    {
        return $this->mapper->fetchBy($criteria);
    }
}
