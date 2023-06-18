<?php

declare(strict_types=1);

namespace Sav\Hydrator\ConstructorParameters\Type;

use Sav\Hydrator\Attribute\ArrayMap;
use Sav\Hydrator\ConstructorParameters\Type;

final class ArrayMapType implements Type
{
    public function __construct(
        private readonly ArrayMap $arrayMap,
    ) {
    }

    public function cast(mixed $value): ?array
    {
        if ($value === null) {
            return null;
        }

        if (is_array($value)) {
            return $value;
        }

        throw new \InvalidArgumentException('Value is not an array.');
    }

    public function getKeyType(mixed $arrayKey, object $context): Type
    {
        return $this->arrayMap->getKeyType($arrayKey, $context);
    }
}
