<?php

declare(strict_types=1);

namespace App\Infrastructure\Mapper\Task;

use App\Entity\Task;
use App\Infrastructure\Mapper\PdoDataMapper;
use App\Infrastructure\Mapper\User\UserMapperInterface;
use PDO;

class PdoTaskMapper extends PdoDataMapper implements TaskMapperInterface
{
    private $userMapper;

    function __construct(PDO $dbConnection, UserMapperInterface $userMapper)
    {
        parent::__construct($dbConnection);
        $this->userMapper = $userMapper;
    }

    public function createTask(Task $task): void
    {
        $taskId = $this->executeInsert($this->getTaskAsArray($task));
        $task->setId($taskId);
    }

    private function getTaskAsArray($task): array
    {
        return [
            'title' => $task->getTitle(),
            'description' => $task->getDescription(),
            'status' => $task->getStatus(),
            'assigneeId' => $task->getAssignee()->getId(),
            'dueDate' => $task->getDuedate()->format('Y-m-d H:i:s'),
            'creationDate' => $task->getDuedate()->format('Y-m-d H:i:s')
        ];
    }

    public function fetchTaskBy(array $valuesMap): ?Task
    {
        $taskValues = $this->executeSelect($valuesMap);
        return $taskValues ? $this->createFromArray($taskValues) : null;
    }

    private function createFromArray(array $taskValues): Task
    {
        $user = $this->userMapper->fetchUserBy(['id' => $taskValues['assigneeId']]);
        $task = new Task(
            $taskValues['title'],
            new \DateTime($taskValues['dueDate']),
            $user,
            new \DateTime($taskValues['creationDate'])
        );
        $task->setId($taskValues['id']);
        return $task;
    }

    public function fetchBy(array $valuesMap): array
    {
        $taskValues = $this->executeSelect($valuesMap);
        $tasks = [];
        if ($taskValues) {
            foreach ($taskValues as $row) {
                $tasks[] = $this->createFromArray($row);
            }
        }
        return $tasks;
    }

    public function updateTask(Task $task): void
    {
        $this->executeUpdate($this->getTaskAsArray($task), ['id' => $task->getId()]);
    }

    public function deleteTask(Task $task): void
    {
        $this->executeDelete(['id' => $task->getId()]);
    }

    protected function getTableName(): string
    {
        return 'task';
    }
}
