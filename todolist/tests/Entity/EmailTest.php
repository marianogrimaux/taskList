<?php

declare(strict_types=1);


namespace Tests\Entity;


use App\Domain\Entity\Email;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{

    public function testBadEmail() : void
    {
        $email = new Email('Mariano@mariano');
        $this->assertFalse($email->isValid());
        $email = new Email('Mariano');
        $this->assertFalse($email->isValid());
    }

    public function testGoodEmail() : void
    {
        $email = new Email('email@email.com');
        $this->assertInstanceOf(Email::class, $email);
        $this->assertEquals('email@email.com', $email->__toString());
    }

    public function testNotEquals() : void
    {
        $email = new Email('email@email.com');
        $email2 = new Email('email.email@email.com');
        $equals = $email->equals($email2);
        $this->assertFalse($equals);
    }

    public function testEquals() : void
    {
        $email = new Email('email@email.com');
        $email2 = new Email('email@email.com');
        $equals = $email->equals($email2);
        $this->assertTrue($equals);
    }
}
