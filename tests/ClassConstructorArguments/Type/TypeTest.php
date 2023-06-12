<?php

declare(strict_types=1);

namespace Sav\Hydrator\ClassConstructorArguments\Type;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Sav\Hydrator\ClassConstructorArguments\TypeInterface;

final class TypeTest extends TestCase
{
    /**
     * @return \Generator<int, array{class-string<TypeInterface>, mixed}>
     */
    public static function typesWithAvailableValues(): \Generator
    {
        yield [BoolType::class, true];
        yield [BoolType::class, null];
        yield [IntType::class, 1];
        yield [IntType::class, null];
        yield [ArrayType::class, []];
        yield [ArrayType::class, null];
        yield [FloatType::class, 1.0];
        yield [FloatType::class, 1];
        yield [FloatType::class, null];
        yield [StringType::class, '1'];
        yield [StringType::class, null];
        yield [MixedType::class, '1'];
        yield [MixedType::class, 1];
        yield [MixedType::class, true];
        yield [MixedType::class, null];
    }

    /**
     * @return \Generator<int, array{class-string<TypeInterface>, mixed}>
     */
    public static function typesWithWrongValues(): \Generator
    {
        yield [BoolType::class, 1];
        yield [IntType::class, '1'];
        yield [ArrayType::class, 1];
        yield [FloatType::class, '1'];
        yield [StringType::class, 1];
    }

    /**
     * @param class-string<TypeInterface> $type
     */
    #[DataProvider('typesWithAvailableValues')]
    public function testTypesWithAvailableValues(string $type, mixed $value): void
    {
        self::expectNotToPerformAssertions();
        $typeClass = new $type();

        $typeClass->cast($value);
    }

    /**
     * @param class-string<TypeInterface> $type
     */
    #[DataProvider('typesWithWrongValues')]
    public function testTypesWithWrongValues(string $type, mixed $value): void
    {
        self::expectException(\InvalidArgumentException::class);
        $typeClass = new $type();

        $typeClass->cast($value);
    }
}
