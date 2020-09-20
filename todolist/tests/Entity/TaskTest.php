<?php

namespace Tests\Entity;

use App\Entity\Task;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{

    /*
     * Test creation date is set when creating the task if not provided.
     */
    public function testCreationDate() : void
    {
        $task = new Task('A simple task', new \DateTime());
        $this->assertNotNull($task->getCreationDate());
    }

    /*
     * Test description is optional.
     */
    public function testDescriptionOnCreation() : void
    {
        $task = new Task('A simple task', new \DateTime());
        $this->assertNull($task->getDescription());
    }

    /*
     * Test that task have a default status
     */
    public function testTaskHaveDefaultStatus() : void
    {
        $this->fail("Not implemented");
    }

}
