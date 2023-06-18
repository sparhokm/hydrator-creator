<?php

declare(strict_types=1);

namespace Sav\Hydrator\ConstructorParameters\Type;

use Sav\Hydrator\ConstructorParameters\Type;

final class FloatType implements Type
{
    public function cast(mixed $value): ?float
    {
        if ($value === null) {
            return null;
        }

        if (is_float($value) || is_int($value)) {
            return (float)$value;
        }

        throw new \InvalidArgumentException('Value is not a float.');
    }
}
