<?php


namespace App\Entity;

use MyCLabs\Enum\Enum;

class TaskStatus extends Enum
{
    private const PENDING = 'Pending';
    private const DONE = 'Done';
    private const DOING = 'Doing';
}
