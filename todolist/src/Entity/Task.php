<?php


namespace App\Entity;


class Task
{
    private $id;
    private $title;
    private $description;
    private $duedate;
    private $creationDate;
    private $status;

    function __construct(string $title,\DateTimeInterface $dueDate ,\DateTimeInterface $creationDate = null)
    {
        $this->title = $title;
        $this->duedate = $dueDate;
        $this->creationDate = $cretionDate ?? new \DateTime();
    }

    /**
     * @param int
     * the task id
     */
    public function setId(int $id) : void
    {
        $this->id = $id;
    }

    /**
     * @return string
     * the task id
     */
    public function getId() : ?int
    {
        return $this->id;
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
    public function getDescription() : ?string
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
     * @return \DateTimeInterface
     */
    public function getDuedate(): \DateTimeInterface
    {
        return $this->duedate;
    }

    /**
     * @param \DateTimeInterface $duedate
     */
    public function setDuedate(\DateTimeInterface $duedate): void
    {
        $this->duedate = $duedate;
    }

    /**
     * @return \DateTime
     */
    public function getCreationDate(): \DateTime
    {
        return $this->creationDate;
    }

}