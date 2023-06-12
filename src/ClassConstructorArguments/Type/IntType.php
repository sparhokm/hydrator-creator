<?php

declare(strict_types=1);

namespace Sav\Hydrator\ClassConstructorArguments\Type;

use Sav\Hydrator\ClassConstructorArguments\TypeInterface;

final class IntType implements TypeInterface
{
    public function cast(mixed $value): ?int
    {
        if ($value === null) {
            return null;
        }

        if (is_int($value)) {
            return $value;
        }

        throw new \InvalidArgumentException('Value is not an int.');
    }
}
