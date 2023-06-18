<?php

declare(strict_types=1);

namespace Sav\Hydrator\ConstructorParameters\Type;

use Sav\Hydrator\ConstructorParameters\Type;

final class MixedType implements Type
{
    public function cast(mixed $value): mixed
    {
        return $value;
    }
}
