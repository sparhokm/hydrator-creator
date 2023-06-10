<?php

declare(strict_types=1);

namespace Sav\Hydrator;

use ReflectionClass;
use ReflectionException;

final class ReflectionClassFactory
{
    /** @var array<class-string, ReflectionClass> */
    private array $reflectionClasses = [];

    /**
     * @param class-string $class
     * @throws ReflectionException
     */
    public function get(string $class): ReflectionClass
    {
        if (array_key_exists($class, $this->reflectionClasses)) {
            return $this->reflectionClasses[$class];
        }

        $reflectionClass = new ReflectionClass($class);
        $this->reflectionClasses[$class] = $reflectionClass;

        return $reflectionClass;
    }

}
