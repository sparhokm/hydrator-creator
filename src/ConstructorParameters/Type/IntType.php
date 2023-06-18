<?php

declare(strict_types=1);

namespace Sav\Hydrator\ConstructorParameters\Type;

use Sav\Hydrator\ConstructorParameters\Type;

final class IntType implements Type
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
