<?php

declare(strict_types=1);

namespace Sav\Hydrator;

use LogicException;
use ReflectionException;
use Sav\Hydrator\ConstructorParameters\ConstructorParameters;
use Sav\Hydrator\ConstructorParameters\Type;
use Sav\Hydrator\ConstructorParameters\TypeFactory;
use Sav\Hydrator\Exception\HydratorException;
use Throwable;

/**
 * @psalm-suppress MixedAssignment
 * @psalm-suppress MixedArgument
 */
final class Hydrator
{
    public function __construct(
        private readonly ConstructorParameters $constructorParameters,
        private readonly DataCaster $dataCaster,
        private readonly ObjectCreator $objectCreator,
    ) {
    }

    public static function init(): self
    {
        return new self(
            new ConstructorParameters(new ReflectionClassFactory(), new TypeFactory()),
            new DataCaster(),
            new ObjectCreator(new ReflectionClassFactory())
        );
    }

    /**
     * @template T
     * @param class-string<T> $class
     * @return T
     *
     * @throws ReflectionException
     * @throws LogicException
     * @throws HydratorException
     */
    public function hydrate(string $class, array $data, object $context = new NullContext()): object
    {
        $parameters = $this->constructorParameters->getParameters($class);

        $exceptions = [];
        $constructorValues = [];
        foreach ($parameters as $parameter) {
            try {
                try {
                    $value = $this->dataCaster->cast($parameter, $data, $context);
                    $constructorValues[$parameter->getName()] = $this->hydrateType(
                        $parameter->getType(),
                        $value,
                        $context
                    );
                } catch (Throwable $throwable) {
                    throw new HydratorException(
                        previous: $throwable,
                        arrayKey: $parameter->getName(),
                    );
                }
            } catch (HydratorException $exception) {
                $exceptions[] = $exception;
                continue;
            }
        }

        if (!empty($exceptions)) {
            throw new HydratorException(previous: $exceptions);
        }

        return $this->objectCreator->create($class, $constructorValues);
    }

    /**
     * @throws ReflectionException
     * @throws LogicException
     * @throws HydratorException
     */
    private function hydrateType(Type $type, mixed $value, object $context): mixed
    {
        if ($value === null) {
            return null;
        }

        $classType = is_a($type, Type\ClassType::class) ? $type->getClass() : null;
        if ($classType) {
            return is_array($value)
                ? $this->hydrate($classType, $value, $context)
                : $this->objectCreator->create($classType, $value);
        }

        if (is_a($type, Type\ArrayMapType::class) && is_array($value)) {
            $exceptions = [];
            foreach ($value as $arrayKey => $subValue) {
                try {
                    $value[$arrayKey] = $this->hydrateType(
                        $type->getKeyType($arrayKey, $context),
                        $subValue,
                        $context
                    );
                } catch (Throwable $throwable) {
                    $exceptions[] = new HydratorException(
                        previous: $throwable,
                        arrayKey: (string)$arrayKey,
                    );
                    continue;
                }
            }

            if (!empty($exceptions)) {
                throw new HydratorException(previous: $exceptions);
            }
        }

        return $value;
    }
}
