<?php

namespace tests\Model;

use PHPUnit\Framework\TestCase;
use src\Exceptions\InvalidArgumentException;
use src\Model\UUID;

class UUIDTests extends TestCase
{
    public function testIncorrectUuid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Не корректный UUID');

        $uuidString = 'gg--------';
        $uuid = new UUID($uuidString);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testToString(): void
    {
        $myUuidString = 'f28262e5-df9c-4fd6-b38e-08421c645349';
        $uuid = new UUID($myUuidString);

        $this->assertEquals($myUuidString, $uuid);
    }

    public function testGenerateUuid(): void
    {
        $myGeneratedUuid = UUID::random();
        $uuid = new UUID($myGeneratedUuid);

        $this->assertEquals($myGeneratedUuid, $uuid);
    }
}
