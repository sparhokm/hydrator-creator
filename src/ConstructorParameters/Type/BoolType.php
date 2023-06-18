<?php

declare(strict_types=1);

namespace Sav\Hydrator\ConstructorParameters\Type;

use InvalidArgumentException;
use Sav\Hydrator\ConstructorParameters\Type;

final class BoolType implements Type
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
