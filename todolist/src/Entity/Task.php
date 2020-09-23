<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use DateTimeInterface;

class Task
{
    private $id;
    private $title;
    private $description;
    private $duedate;
    private $creationDate;
    private $status;
    private $assignee;

    function __construct(
        string $title,
        DateTimeInterface $dueDate,
        User $asignee,
        DateTimeInterface $creationDate = null
    ) {
        $this->title = $title;
        $this->duedate = $dueDate;
        $this->creationDate = $creationDate ?? new DateTime();
        $this->status = TaskStatus::PENDING();
        $this->assignee = $asignee;
    }

    /**
     * @return string
     * the task id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int
     * the task id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     * the title of the task
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * the title of the task
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return DateTimeInterface
     */
    public function getDuedate(): DateTimeInterface
    {
        return $this->duedate;
    }

    /**
     * @param DateTimeInterface $duedate
     */
    public function setDuedate(DateTimeInterface $duedate): void
    {
        $this->duedate = $duedate;
    }

    /**
     * @return DateTime
     */
    public function getCreationDate(): DateTime
    {
        return $this->creationDate;
    }

    /**
     * @return TaskStatus
     */
    public function getStatus(): TaskStatus
    {
        return $this->status;
    }

    /**
     * @param TaskStatus $status
     */
    public function setStatus(TaskStatus $status): void
    {
        $this->status = $status;
    }

    /**
     * @return User
     */
    public function getAssignee(): User
    {
        return $this->assignee;
    }

}
