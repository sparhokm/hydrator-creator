<?php

declare(strict_types=1);

namespace Sav\Hydrator\ClassConstructorArguments;

use InvalidArgumentException;

interface TypeInterface
{
    /**
     * @throws InvalidArgumentException
     */
    public function cast(mixed $value): mixed;
}
