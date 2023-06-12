<?php

declare(strict_types=1);

namespace Sav\Hydrator\ClassConstructorArguments\Type;

use InvalidArgumentException;
use Sav\Hydrator\ClassConstructorArguments\TypeInterface;

final class BoolType implements TypeInterface
{
    public function cast(mixed $value): ?bool
    {
        if ($value === null) {
            return null;
        }

        if (is_bool($value)) {
            return $value;
        }

        throw new InvalidArgumentException('Value is not a bool.');
    }
}
