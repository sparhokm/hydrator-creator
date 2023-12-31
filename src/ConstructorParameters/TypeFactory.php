<?php

declare(strict_types=1);

namespace Sav\Hydrator\ConstructorParameters;

use LogicException;
use ReflectionNamedType;
use Sav\Hydrator\Attribute\ArrayMap;

/**
 * @psalm-type typesMap = array<string, class-string<Type>>
 */
final class TypeFactory
{
    /** @var typesMap */
    private const DEFAULT_TYPES = [
        'bool' => Type\BoolType::class,
        'float' => Type\FloatType::class,
        'int' => Type\IntType::class,
        'string' => Type\StringType::class,
        'array' => Type\ArrayType::class,
        'mixed' => Type\MixedType::class,
    ];

    /** @var typesMap */
    private readonly array $types;

    /**
     * @param typesMap $types
     */
    public function __construct(array $types = [])
    {
        $this->types = array_merge(self::DEFAULT_TYPES, $types);
    }

    /**
     * @throws LogicException
     */
    public function get(?ReflectionNamedType $reflectionType, ?ArrayMap $arrayMap): Type
    {
        if ($reflectionType === null) {
            return $this->getTypeByName('mixed');
        }

        if ($arrayMap !== null) {
            return $this->getArrayMapType($reflectionType, $arrayMap);
        }

        return $this->getTypeByName($reflectionType->getName());
    }

    /**
     * @throws LogicException
     */
    private function getArrayMapType(
        ReflectionNamedType $reflectionType,
        ArrayMap $arrayMap,
    ): Type\ArrayMapType {
        if ($reflectionType->getName() !== 'array') {
            throw new LogicException('ArrayMap type can set only for array');
        }

        return new Type\ArrayMapType($arrayMap);
    }

    /**
     * @throws LogicException
     */
    private function getTypeByName(string $typeName): Type
    {
        if (array_key_exists($typeName, $this->types)) {
            return new $this->types[$typeName]();
        }

        if (class_exists($typeName)) {
            return new Type\ClassType($typeName);
        }

        throw new LogicException('Unsupported type ' . $typeName);
    }
}
