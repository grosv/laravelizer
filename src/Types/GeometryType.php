<?php

namespace Laravelizer\Types;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class GeometryType extends Type
{

    const GEOMETRY = 'geometry';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'GEOMETRY';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return sprintf('AsText(%s)', $value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return sprintf('ST_GeomFromText(%s)', $value);
    }

    public function getName()
    {
        return self::GEOMETRY;
    }
}