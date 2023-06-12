<?php

declare(strict_types=1);

namespace Sav\Hydrator\ClassConstructorArguments\Type;

use Sav\Hydrator\ClassConstructorArguments\TypeInterface;

final class StringType implements TypeInterface
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
