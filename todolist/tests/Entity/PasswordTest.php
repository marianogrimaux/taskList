<?php

declare(strict_types=1);


namespace Tests\Entity;

use App\Domain\Entity\Password;
use PHPUnit\Framework\TestCase;

class PasswordTest extends TestCase
{
    function testBadPassword()
    {
        $password = new Password('asd');
        $this->assertFalse($password->isValid());

    }

    function testGoodPassword()
    {
        $password = new Password('12345678');
        $this->assertInstanceOf(Password::class, $password);
        $this->assertEquals('12345678', $password->__toString());
    }
}
