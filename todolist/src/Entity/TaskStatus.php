<?php


namespace App\Entity;

use MyCLabs\Enum\Enum;

class TaskStatus extends Enum
{
    private const PENDING = 'Pending';
    private const DONE = 'Done';
    private const DOING = 'Doing';

    public static function PENDING() : self
    {
        return new TaskStatus(self::PENDING);
    }

    public static function DONE() : self
    {
        return new TaskStatus(self::DONE);
    }

    public static function DOING() : self
    {
        return new TaskStatus(self::DOING);
    }
}
