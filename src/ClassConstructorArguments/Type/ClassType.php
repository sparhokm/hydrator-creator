<?php

declare(strict_types=1);

namespace Sav\Hydrator\ClassConstructorArguments\Type;

use Sav\Hydrator\ClassConstructorArguments\TypeInterface;

final class ClassType implements TypeInterface
{
    public function __construct(private readonly string $class)
    {
    }

    public function cast(mixed $value): mixed
    {
        return $value;
    }

    public function getClass(): string
    {
        return $this->class;
    }
}
