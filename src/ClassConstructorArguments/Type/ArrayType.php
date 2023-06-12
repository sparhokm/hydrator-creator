<?php

declare(strict_types=1);

namespace Sav\Hydrator\ClassConstructorArguments\Type;

use InvalidArgumentException;
use Sav\Hydrator\ClassConstructorArguments\TypeInterface;

final class ArrayType implements TypeInterface
{
    public function cast(mixed $value): ?array
    {
        if ($value === null) {
            return null;
        }

        if (is_array($value)) {
            return $value;
        }

        throw new InvalidArgumentException('Value is not an array.');
    }
}
