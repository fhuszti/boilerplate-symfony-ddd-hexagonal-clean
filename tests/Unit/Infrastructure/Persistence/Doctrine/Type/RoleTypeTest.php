<?php

namespace App\Tests\Unit\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\Security\RoleEnum;
use App\Infrastructure\Exception\InvalidTypeException;
use App\Infrastructure\Persistence\Doctrine\Type\RoleType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use PHPUnit\Framework\TestCase;

class RoleTypeTest extends TestCase
{
    private RoleType $roleType;
    private AbstractPlatform $platformMock;

    protected function setUp(): void
    {
        $this->roleType = new RoleType();
        $this->platformMock = $this->createMock(AbstractPlatform::class);
    }

    public function testGetSQLDeclaration(): void
    {
        $this->assertEquals('VARCHAR(50)', $this->roleType->getSQLDeclaration([], $this->platformMock));
    }

    public function testConvertToPHPValue(): void
    {
        $result = $this->roleType->convertToPHPValue('admin', $this->platformMock);
        $this->assertInstanceOf(RoleEnum::class, $result);
        $this->assertEquals(RoleEnum::Admin, $result);
    }

    public function testConvertToPHPValueWithNull(): void
    {
        $this->expectException(InvalidTypeException::class);
        $result = $this->roleType->convertToPHPValue(null, $this->platformMock);
    }

    public function testConvertToPHPValueWithInvalidType(): void
    {
        $this->expectException(InvalidTypeException::class);
        $this->roleType->convertToPHPValue(123, $this->platformMock);
    }

    public function testConvertToPHPValueWithUnknownValue(): void
    {
        $this->expectException(\ValueError::class);
        $this->roleType->convertToPHPValue('Unknown', $this->platformMock); // Unknown role
    }

    public function testConvertToDatabaseValue(): void
    {
        $enum = RoleEnum::Admin;
        $result = $this->roleType->convertToDatabaseValue($enum, $this->platformMock);
        $this->assertEquals($enum->value, $result);
    }

    public function testConvertToDatabaseValueWithNull(): void
    {
        $this->expectException(InvalidTypeException::class);
        $result = $this->roleType->convertToDatabaseValue(null, $this->platformMock);
    }

    public function testConvertToDatabaseValueWithInvalidType(): void
    {
        $this->expectException(InvalidTypeException::class);
        $result = $this->roleType->convertToDatabaseValue(new \stdClass(), $this->platformMock);
    }

    public function testGetName(): void
    {
        $this->assertEquals('role_enum', $this->roleType->getName());
    }
}
