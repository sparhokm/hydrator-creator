<?php

declare(strict_types=1);

namespace Sav\Hydrator\Tests\ConstructorParameters\TypeFactory;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Sav\Hydrator\Attribute\ArrayMap;
use Sav\Hydrator\ConstructorParameters\Type\ArrayMapType;
use Sav\Hydrator\ConstructorParameters\Type\ClassType;
use Sav\Hydrator\ConstructorParameters\Type\MixedType;
use Sav\Hydrator\ConstructorParameters\Type\StringType;
use Sav\Hydrator\ConstructorParameters\TypeFactory;

final class TypeFactoryTest extends TestCase
{
    public function testOverdriveType(): void
    {
        $typeClass = StringType::class;
        $typeFactory = new TypeFactory(['bool' => $typeClass]);
        $boolVarParameter = $this->getReflectionNamedType('boolVar');

        $returnType = $typeFactory->get($boolVarParameter, null);

        self::assertInstanceOf($typeClass, $returnType);
    }

    public function testExtendType(): void
    {
        $typeClass = StringType::class;
        $typeFactory = new TypeFactory([\DateTimeImmutable::class => $typeClass]);
        $dateTimeImmutableParameter = $this->getReflectionNamedType('dateTimeImmutable');

        $returnType = $typeFactory->get($dateTimeImmutableParameter, null);

        self::assertInstanceOf($typeClass, $returnType);
    }

    public function testReturnMixedType(): void
    {
        $typeFactory = new TypeFactory();

        $returnType = $typeFactory->get(null, null);

        self::assertInstanceOf(MixedType::class, $returnType);
    }

    public function testReturnClassType(): void
    {
        $class = \DateTimeImmutable::class;
        $typeFactory = new TypeFactory();
        $dateTimeImmutableParameter = $this->getReflectionNamedType('dateTimeImmutable');

        $returnType = $typeFactory->get($dateTimeImmutableParameter, null);

        self::assertInstanceOf(ClassType::class, $returnType);
        self::assertSame($returnType->getClass(), $class);
    }

    public function testReturnArrayMapType(): void
    {
        $typeFactory = new TypeFactory();
        $arrayVar = $this->getReflectionNamedType('arrayVar');

        $returnType = $typeFactory->get($arrayVar, self::createStub(ArrayMap::class));

        self::assertInstanceOf(ArrayMapType::class, $returnType);
    }

    public function testThrowReturnArrayMapForNotArray(): void
    {
        $typeFactory = new TypeFactory();
        $boolVar = $this->getReflectionNamedType('boolVar');
        self::expectException(\LogicException::class);

        $typeFactory->get($boolVar, self::createStub(ArrayMap::class));
    }

    public function testThrowIfTypeNotExist(): void
    {
        $typeFactory = new TypeFactory();
        $objectVar = $this->getReflectionNamedType('objectVar');
        self::expectException(\LogicException::class);

        $typeFactory->get($objectVar, null);
    }

    private function getReflectionNamedType(string $name): \ReflectionNamedType
    {
        $parameters = (new ReflectionClass(StubClass::class))->getConstructor()?->getParameters();
        foreach ($parameters ?? [] as $parameter) {
            $type = $parameter->getType();
            if (
                $type instanceof \ReflectionNamedType
                && $parameter->getName() === $name
            ) {
                return $type;
            }
        }

        throw new \Exception('ReflectionNamedType not found');
    }
}
