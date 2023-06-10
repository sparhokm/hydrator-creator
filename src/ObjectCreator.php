<?php

declare(strict_types=1);

namespace Sav\Hydrator;

use InvalidArgumentException;
use ReflectionException;

final class ObjectCreator
{
    public function __construct(
        private readonly ReflectionClassFactory $reflectionClassFactory,
    ) {
    }

    /**
     * @template T
     *
     * @param class-string<T> $class
     * @param scalar|array $args
     * @return T
     *
     * @psalm-suppress MixedMethodCall
     * @throws ReflectionException
     * @throws InvalidArgumentException
     */
    public function create(string $class, mixed $args): object
    {
        $classReflection = $this->reflectionClassFactory->get($class);
        if (!$classReflection->isInstantiable()) {
            throw new InvalidArgumentException("Class $class is not instantiable.");
        }
        if (is_scalar($args)) {
            return new $class($args);
        }
        return new $class(...$args);
    }
}
