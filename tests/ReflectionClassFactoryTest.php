<?php

declare(strict_types=1);

namespace Sav\Hydrator\Tests;

use DateTimeImmutable;
use ReflectionException;
use Sav\Hydrator\ReflectionClassFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ReflectionClassFactory::class)]
final class ReflectionClassFactoryTest extends TestCase
{
    public function testReturnReflection(): void
    {
        $reflectionFactory = new ReflectionClassFactory();
        $class = DateTimeImmutable::class;

        $dateTimeImmutableReflection = $reflectionFactory->get($class);

        self::assertEquals($dateTimeImmutableReflection->getName(), $class);
    }

    public function testReturnReflectionFromMemoize(): void
    {
        $reflectionFactory = new ReflectionClassFactory();
        $class = DateTimeImmutable::class;

        $dateTimeImmutableReflection1 = $reflectionFactory->get($class);
        $dateTimeImmutableReflection2 = $reflectionFactory->get($class);

        self::assertSame($dateTimeImmutableReflection1, $dateTimeImmutableReflection2);
    }

    public function testThrowWhenClassNotExist(): void
    {
        $reflectionFactory = new ReflectionClassFactory();
        $this->expectException(ReflectionException::class);

        /** @psalm-suppress ArgumentTypeCoercion, UndefinedClass */
        $reflectionFactory->get('ClassNotExist');
    }
}
