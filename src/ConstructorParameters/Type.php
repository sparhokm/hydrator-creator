<?php

declare(strict_types=1);

namespace Sav\Hydrator\ConstructorParameters;

use InvalidArgumentException;

interface Type
{
    /**
     * @throws InvalidArgumentException
     */
    public function cast(mixed $value): mixed;
}
