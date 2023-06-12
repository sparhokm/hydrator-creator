<?php

declare(strict_types=1);

namespace Sav\Hydrator\ObjectCreator;

use DateTimeImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sav\Hydrator\ObjectCreator;
use Sav\Hydrator\ReflectionClassFactory;
use TypeError;

#[CoversClass(ObjectCreator::class)]
final class ObjectCreatorTest extends TestCase
{
    public function testCreateDateTimeImmutable(): void
    {
        $objectCreator = $this->getObjectCreator();
        $dateString = (new DateTimeImmutable())->format('c');

        $date = $objectCreator->create(DateTimeImmutable::class, $dateString);

        self::assertEquals($dateString, $date->format('c'));
    }

    public function testSuccessCreateSimpleClass(): void
    {
        $objectCreator = $this->getObjectCreator();
        $testData = [
            'string' => 'text',
            'bool' => true,
            'int' => 10,
            'dateTimeImmutable' => new DateTimeImmutable(),
        ];

        $object = $objectCreator->create(StubSimpleClass::class, $testData);

        self::assertSame($object->string, $testData['string']);
        self::assertSame($object->bool, $testData['bool']);
        self::assertSame($object->int, $testData['int']);
        self::assertSame($object->dateTimeImmutable, $testData['dateTimeImmutable']);
    }

    public function testTryCreateClassWithWrongTypes(): void
    {
        $objectCreator = $this->getObjectCreator();
        $testData = [
            'string' => 'text',
            'bool' => 10,
            'int' => 10,
            'dateTimeImmutable' => new DateTimeImmutable(),
        ];
        $this->expectException(TypeError::class);

        $objectCreator->create(StubSimpleClass::class, $testData);
    }

    public function testTryCreateNotInstantiableClass(): void
    {
        $objectCreator = $this->getObjectCreator();
        $this->expectException(InvalidArgumentException::class);

        $objectCreator->create(StubNotInstantiableClass::class, []);
    }

    public function testTryCreateClassWithInternalException(): void
    {
        $objectCreator = $this->getObjectCreator();
        $this->expectException(InternalException::class);

        $objectCreator->create(StubInternalException::class, []);
    }

    private function getObjectCreator(): ObjectCreator
    {
        return new ObjectCreator(new ReflectionClassFactory());
    }
}
