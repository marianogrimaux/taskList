<?php

declare(strict_types=1);


namespace App\Domain\Repository;

use App\Domain\Entity\Task;

interface TaskRepositoryInterface
{
    public function findById(int $id): Task;

    public function save(Task $task): void;

    public function delete(Task $task): void;

    public function findBy(array $criteria): array;
}
