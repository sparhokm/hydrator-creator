<?php

declare(strict_types=1);

namespace Sav\Hydrator\ConstructorParameters\Type;

use InvalidArgumentException;
use Sav\Hydrator\ConstructorParameters\Type;

final class ArrayType implements Type
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
