<?php


namespace App\Infrastructure\Mapper\Task;

use App\Entity\Task;

interface TaskMapperInterface
{
    public function create(): void;

    public function fetchOne(): Task;

    public function fetchBy(): array;

    public function update(Task $task): void;

    public function delete(Task $task): void;
}
