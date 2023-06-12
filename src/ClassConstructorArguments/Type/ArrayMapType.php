<?php

declare(strict_types=1);

namespace Sav\Hydrator\ClassConstructorArguments\Type;

use Sav\Hydrator\ClassConstructorArguments\ArrayMapInterface;
use Sav\Hydrator\ClassConstructorArguments\TypeInterface;

final class ArrayMapType implements TypeInterface
{
    public function __construct(
        private readonly ArrayMapInterface $arrayMap,
    ) {
    }

    public function cast(mixed $value): ?array
    {
        if ($value === null) {
            return null;
        }

        if (is_array($value)) {
            return $value;
        }

        throw new \InvalidArgumentException('Value is not an array.');
    }

    public function getKeyType(mixed $arrayKey, object $context): TypeInterface
    {
        return $this->arrayMap->getKeyType($arrayKey, $context);
    }
}
