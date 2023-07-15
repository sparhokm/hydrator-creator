<?php

declare(strict_types=1);

namespace Sav\Hydrator\Tests\Hydrator;

use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sav\Hydrator\ConstructorParameters\ConstructorParameters;
use Sav\Hydrator\ConstructorParameters\TypeFactory;
use Sav\Hydrator\DataCaster;
use Sav\Hydrator\Exception\HydratorException;
use Sav\Hydrator\Hydrator;
use Sav\Hydrator\ObjectCreator;
use Sav\Hydrator\ReflectionClassFactory;

#[CoversClass(Hydrator::class)]
#[CoversClass(DataCaster::class)]
final class HydratorTest extends TestCase
{
    public function testSuccessCreateSimpleClass(): void
    {
        $hydrator = new Hydrator(
            new ConstructorParameters(new ReflectionClassFactory(), new TypeFactory()),
            new DataCaster(),
            new ObjectCreator(new ReflectionClassFactory())
        );
        $testData = [
            'float' => 3.0,
            'string' => 'text',
            'bool' => true,
            'int' => 10,
            'oldStubSubClass' => [
                'dateTimeImmutable' => new DateTimeImmutable(),
            ],
            'createdStubSubClass' => new StubSubClass(new DateTimeImmutable()),
            'nullStubSubClass' => null,
            'arrayStubSubClass' => [
                ['dateTimeImmutable' => new DateTimeImmutable()],
                ['dateTimeImmutable' => '2023-12-12 07:07:07', 'intWithDefault' => 3],
                new StubSubClass(new DateTimeImmutable(), 4),
            ]
        ];

        $obj = $hydrator->hydrate(StubClass::class, $testData, new \stdClass());

        self::assertSame($obj->float, $testData['float']);
        self::assertSame($obj->string, $testData['string']);
        self::assertSame($obj->bool, $testData['bool']);
        self::assertSame($obj->int, $testData['int']);
        self::assertSame(5, $obj->defaultInt);
        self::assertSame($obj->stubSubClass->dateTimeImmutable, $testData['oldStubSubClass']['dateTimeImmutable']);
        self::assertSame(2, $obj->stubSubClass->intWithDefault);
        self::assertNull($obj->nullStubSubClass);
        self::assertSame($obj->createdStubSubClass, $testData['createdStubSubClass']);
        self::assertSame(
            $obj->arrayStubSubClass[0]->dateTimeImmutable,
            $testData['arrayStubSubClass'][0]['dateTimeImmutable']
        );
        self::assertSame(2, $obj->arrayStubSubClass[0]->intWithDefault);
        self::assertEquals(
            $obj->arrayStubSubClass[1]->dateTimeImmutable,
            new DateTimeImmutable($testData['arrayStubSubClass'][1]['dateTimeImmutable'])
        );
        self::assertSame(3, $obj->arrayStubSubClass[1]->intWithDefault);
        self::assertSame(4, $obj->arrayStubSubClass[2]->intWithDefault);
    }

    public function testTry(): void
    {
        $this->expectException(HydratorException::class);

        $hydrator = new Hydrator(
            new ConstructorParameters(new ReflectionClassFactory(), new TypeFactory()),
            new DataCaster(),
            new ObjectCreator(new ReflectionClassFactory())
        );
        $testData = [
            'a' => null,
            'stubSubClass' => [
                'dateTimeImmutable' => 'asdasdada',
                'intWithDefault' => 'asd'
            ],
            'arrayStubSubClass' => [
                ['dateTimeImmutable' => 'asdsa'],
                ['dateTimeImmutable' => '2023-12-12 07:07:07', 'intWithDefault' => 'ada'],
                new StubSubClass(new DateTimeImmutable(), 4),
            ]
        ];

        $hydrator->hydrate(StubClass2::class, $testData, new \stdClass());
    }
}
