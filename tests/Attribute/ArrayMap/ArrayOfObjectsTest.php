<?php

declare(strict_types=1);

namespace Sav\Hydrator\Tests\Attribute\ArrayMap;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sav\Hydrator\Attribute\ArrayMap\ArrayOfObjects;
use Sav\Hydrator\ConstructorParameters\Type\ArrayMapType;
use Sav\Hydrator\ConstructorParameters\Type\ArrayType;
use Sav\Hydrator\ConstructorParameters\Type\ClassType;
use Sav\Hydrator\Tests\ConstructorParametersHelper;

#[CoversClass(ArrayOfObjects::class)]
final class ArrayOfObjectsTest extends TestCase
{
    public function testArrayOfObject(): void
    {
        $parameters = ConstructorParametersHelper::getParameters(StubClass::class);
        $context = new \stdClass();

        $arrayOfObjects = ConstructorParametersHelper::getParameterByName($parameters, 'arrayOfObjects');

        self::assertInstanceOf(ArrayMapType::class, $type = $arrayOfObjects->getType());
        self::assertInstanceOf(ClassType::class, $classType = $type->getKeyType(0, $context));
        self::assertSame(StubClass::class, $classType->getClass());
    }

    public function testArrayType(): void
    {
        $parameters = ConstructorParametersHelper::getParameters(StubClass::class);

        $array = ConstructorParametersHelper::getParameterByName($parameters, 'array');

        self::assertInstanceOf(ArrayType::class, $array->getType());
    }
}
