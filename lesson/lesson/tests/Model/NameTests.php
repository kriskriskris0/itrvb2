<?php

namespace tests\Model;

use PHPUnit\Framework\TestCase;
use src\Model\Name;

class NameTests extends TestCase
{
    private Name $name;

    protected function setUp(): void
    {
        $this->name = new Name('fN', 'lN');
    }

    public function testGetData(): void
    {
        $firstName = 'fN';
        $lastName = 'lN';

        $this->assertSame($firstName, $this->name->getFirstName());
        $this->assertSame($lastName, $this->name->getLastName());
    }

    public function testToString(): void
    {
        $fullName = 'fN lN';
        $this->assertSame($fullName, $this->name->__toString());
    }
}
