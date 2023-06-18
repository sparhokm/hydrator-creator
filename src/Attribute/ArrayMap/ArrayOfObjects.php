<?php

declare(strict_types=1);

namespace Sav\Hydrator\Attribute\ArrayMap;

use Attribute;
use Sav\Hydrator\Attribute\ArrayMap;
use Sav\Hydrator\ConstructorParameters\Type;
use Sav\Hydrator\ConstructorParameters\Type\ClassType;

#[Attribute(Attribute::TARGET_PARAMETER | Attribute::TARGET_PROPERTY | Attribute::TARGET_CLASS)]
final class ArrayOfObjects implements ArrayMap
{
    private readonly ClassType $type;

    public function __construct(string $class)
    {
        $this->type = new ClassType($class);
    }

    public function getKeyType(mixed $arrayKey, object $context): Type
    {
        return $this->type;
    }
}
