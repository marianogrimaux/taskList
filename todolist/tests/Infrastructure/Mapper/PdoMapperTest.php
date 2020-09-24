<?php

declare(strict_types=1);


namespace Tests\Infrastructure\Mapper;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

abstract class PdoMapperTest extends TestCase
{
    protected function buildPdoMockWithQueryCheck(string $queryToCheck, MockObject $statement): MockObject
    {
        $connection = $this->getMockedPdo();
        $connection->expects($this->once())
            ->method('prepare')
            ->will(
                $this->returnCallback(
                    function ($query) use ($queryToCheck, $statement) {
                        $this->assertEquals(
                            $queryToCheck,
                            $query
                        );
                        return $statement;
                    }
                )
            );
        return $connection;
    }

    protected function getMockedPdo(MockObject $statement = null): MockObject
    {
        $db = $this->getMockBuilder('\PDO')
            ->disableOriginalConstructor()
            ->getMock();
        if ($statement) {
            $db->method('prepare')->willReturn($statement);
        }
        return $db;
    }

    protected function buildPdoStatementMockWithValueMapCheck(array $keysToCheck): MockObject
    {
        $statement = $this->getMockedStatement();
        $statement->expects($this->once())
            ->method('execute')
            ->willReturn(
                $this->returnCallback(
                    function (array $values) use ($keysToCheck) {
                        foreach ($keysToCheck as $key) {
                            $this->assertTrue(array_key_exists($key, $values));
                        }
                    }
                )
            );
        return $statement;
    }

    protected function getMockedStatement(): MockObject
    {
        $statement = $this->getMockBuilder('\PDOStatement')
            ->disableOriginalConstructor()
            ->getMock();
        return $statement;
    }

}
