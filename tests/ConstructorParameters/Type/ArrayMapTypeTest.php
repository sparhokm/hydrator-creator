<?php

declare(strict_types=1);

namespace Sav\Hydrator\Tests\ConstructorParameters\Type;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sav\Hydrator\Attribute\ArrayMap;
use Sav\Hydrator\ConstructorParameters\Type\ArrayMapType;
use Sav\Hydrator\ConstructorParameters\Type\StringType;

#[CoversClass(ArrayMapType::class)]
final class ArrayMapTypeTest extends TestCase
{
    public function testTypesWithAvailableValues(): void
    {
        self::expectNotToPerformAssertions();
        $typeClass = new ArrayMapType(self::createStub(ArrayMap::class));

        $typeClass->cast([]);
        $typeClass->cast(null);
    }

    public function testTypesWithWrongValues(): void
    {
        self::expectException(\InvalidArgumentException::class);
        $typeClass = new ArrayMapType(self::createStub(ArrayMap::class));

        $typeClass->cast('1');
    }

    public function testReturnTypeByKey(): void
    {
        $stringType = new StringType();
        $arrayMap = self::createStub(ArrayMap::class);
        $arrayMap->method('getKeyType')->willReturn($stringType);
        $typeClass = new ArrayMapType($arrayMap);

        self::assertSame($stringType, $typeClass->getKeyType(0, new \stdClass()));
        self::assertSame($stringType, $typeClass->getKeyType('1', new \stdClass()));
    }
}
