<?php

declare(strict_types=1);

namespace Sav\Hydrator\ConstructorParameters\Type;

use Sav\Hydrator\ConstructorParameters\Type;

final class StringType implements Type
{
    public function cast(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if (is_string($value)) {
            return $value;
        }

        throw new \InvalidArgumentException('Value is not a string.');
    }
}
