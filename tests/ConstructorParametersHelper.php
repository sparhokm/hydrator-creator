<?php

declare(strict_types=1);

namespace Sav\Hydrator\Tests;

use Sav\Hydrator\ConstructorParameters\Parameter;
use Sav\Hydrator\ConstructorParameters\ConstructorParameters;
use Sav\Hydrator\ConstructorParameters\TypeFactory;
use Sav\Hydrator\ReflectionClassFactory;

final class ConstructorParametersHelper
{
    /**
     * @param class-string $class
     * @return list<Parameter>
     */
    public static function getParameters(string $class): array
    {
        return (new ConstructorParameters(new ReflectionClassFactory(), new TypeFactory()))->getParameters($class);
    }

    /**
     * @param array<Parameter> $parameters
     */
    public static function getParameterByName(array $parameters, string $name): Parameter
    {
        foreach ($parameters as $parameter) {
            if ($parameter->getName() === $name) {
                return $parameter;
            }
        }

        throw new \RuntimeException('Parameter not found');
    }
}
