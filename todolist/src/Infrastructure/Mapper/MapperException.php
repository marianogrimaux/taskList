<?php

declare(strict_types=1);


namespace App\Infrastructure\Mapper;


use Throwable;

class MapperException extends \Exception
{
    const MESSAGE_PREPEND = 'Exception running query: ';

    function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct(self::MESSAGE_PREPEND . $message, $code, $previous);
    }

}
