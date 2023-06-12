<?php

declare(strict_types=1);

namespace Sav\Hydrator\ClassConstructorArguments\Type;

use Sav\Hydrator\ClassConstructorArguments\TypeInterface;

final class MixedType implements TypeInterface
{
    public function cast(mixed $value): mixed
    {
        return $value;
    }
}
