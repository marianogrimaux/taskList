<?php

declare(strict_types=1);

namespace Tests\Entity;

use App\Domain\Entity\Task;
use App\Domain\Entity\User;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{

    /*
     * Test creation date is set when creating the task if not provided.
     */
    public function testCreationDate() : void
    {
        $task = new Task('A simple task', new \DateTime(), $this->getUser());
        $this->assertNotNull($task->getCreationDate());
    }

    /*
     * Test description is optional.
     */
    public function testDescriptionOnCreation() : void
    {
        $task = new Task('A simple task', new \DateTime(), $this->getUser());
        $this->assertNull($task->getDescription());
    }

    /*
     * Test that task have a default status
     * Default status for tasks is PENDING
     */
    public function testTaskHaveDefaultStatus() : void
    {
        $task = new Task('A simple task', new \DateTime(), $this->getUser());
        $this->assertNotNull($task->getStatus());
        $this->assertEquals('Pending', $task->getStatus()->getValue());
    }

    private function getUser() : User
    {
        return new User('Mariano', 'mariano@mariano.com');
    }

}
