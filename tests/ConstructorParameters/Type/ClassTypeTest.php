<?php

declare(strict_types=1);

namespace Sav\Hydrator\Tests\ConstructorParameters\Type;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sav\Hydrator\ConstructorParameters\Type\ClassType;

#[CoversClass(ClassType::class)]
final class ClassTypeTest extends TestCase
{
    public function testTypeCast(): void
    {
        $value = [];
        $typeClass = new ClassType(\DateTimeImmutable::class);

        $returnValue = $typeClass->cast($value);

        self::assertSame($value, $returnValue);
    }

    public function testReturnClassName(): void
    {
        $class = \DateTimeImmutable::class;
        $typeClass = new ClassType($class);

        $classReturn = $typeClass->getClass();

        self::assertSame($class, $classReturn);
    }
}
