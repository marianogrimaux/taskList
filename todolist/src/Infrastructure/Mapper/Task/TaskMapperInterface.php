<?php

namespace App\Infrastructure\Mapper\Task;

use App\Entity\Task;

interface TaskMapperInterface
{
    public function createTask(Task $task): void;

    public function fetchTaskBy(array $valuesMap): ?Task;

    public function fetchBy(array $valuesMap): array;

    public function updateTask(Task $task): void;

    public function deleteTask(Task $task): void;
}
