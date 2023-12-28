<?php

namespace App\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\Security\RoleEnum;
use App\Infrastructure\Exception\InvalidTypeException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class RoleType extends Type
{
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'VARCHAR(50)';
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): RoleEnum
    {
        if (!is_string($value)) {
            throw new InvalidTypeException('string', gettype($value));
        }

        return RoleEnum::from($value);
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): mixed
    {
        if (!$value instanceof RoleEnum) {
            throw new InvalidTypeException('RoleEnum', gettype($value));
        }

        return $value->value;
    }

    public function getName(): string
    {
        return 'role_enum';
    }
}
