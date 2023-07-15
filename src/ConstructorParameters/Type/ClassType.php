<?php

declare(strict_types=1);

namespace Sav\Hydrator\ConstructorParameters\Type;

use Sav\Hydrator\ConstructorParameters\Type;

final class ClassType implements Type
{
    /**
     * @param class-string $class
     */
    public function __construct(private readonly string $class)
    {
    }

    public function cast(mixed $value): mixed
    {
        return $value;
    }

    /**
     * @return class-string
     */
    public function getClass(): string
    {
        return $this->class;
    }
}
