<?php

declare(strict_types=1);

namespace Sav\Hydrator\Tests\ConstructorParameters\Type;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Sav\Hydrator\ConstructorParameters\Type;

final class TypeTest extends TestCase
{
    /**
     * @return \Generator<int, array{class-string<Type>, mixed}>
     */
    public static function typesWithAvailableValues(): \Generator
    {
        yield [Type\BoolType::class, true];
        yield [Type\BoolType::class, null];
        yield [Type\IntType::class, 1];
        yield [Type\IntType::class, null];
        yield [Type\ArrayType::class, []];
        yield [Type\ArrayType::class, null];
        yield [Type\FloatType::class, 1.0];
        yield [Type\FloatType::class, 1];
        yield [Type\FloatType::class, null];
        yield [Type\StringType::class, '1'];
        yield [Type\StringType::class, null];
        yield [Type\MixedType::class, '1'];
        yield [Type\MixedType::class, 1];
        yield [Type\MixedType::class, true];
        yield [Type\MixedType::class, null];
    }

    /**
     * @return \Generator<int, array{class-string<Type>, mixed}>
     */
    public static function typesWithWrongValues(): \Generator
    {
        yield [Type\BoolType::class, 1];
        yield [Type\IntType::class, '1'];
        yield [Type\ArrayType::class, 1];
        yield [Type\FloatType::class, '1'];
        yield [Type\StringType::class, 1];
    }

    /**
     * @param class-string<Type> $type
     */
    #[DataProvider('typesWithAvailableValues')]
    public function testTypesWithAvailableValues(string $type, mixed $value): void
    {
        self::expectNotToPerformAssertions();
        $typeClass = new $type();

        $typeClass->cast($value);
    }

    /**
     * @param class-string<Type> $type
     */
    #[DataProvider('typesWithWrongValues')]
    public function testTypesWithWrongValues(string $type, mixed $value): void
    {
        self::expectException(\InvalidArgumentException::class);
        $typeClass = new $type();

        $typeClass->cast($value);
    }
}
