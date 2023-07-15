<?php

declare(strict_types=1);

namespace Sav\Hydrator\Tests\Exception;

use PHPUnit\Framework\TestCase;
use Sav\Hydrator\Exception\HydratorException;

final class HydratorExceptionTest extends TestCase
{
    public function testManyPreviousExceptionsOfHydratorException(): void
    {
        $previousThrowable = new \Exception($message = 'errorMessage');
        $hydratorExceptionPrevious0 = new HydratorException($previousThrowable, $previousKey0 = '0');
        $hydratorExceptionPrevious1 = new HydratorException($previousThrowable, $previousKey1 = '1');
        $hydratorException = new HydratorException([$hydratorExceptionPrevious0, $hydratorExceptionPrevious1], $key = 'someKey');

        $mainHydratorException = new HydratorException($hydratorException);

        self::assertSame($hydratorExceptionPrevious0->getArrayKey(), $previousKey0);
        self::assertSame($hydratorExceptionPrevious0->getPreviousThrowable(), $previousThrowable);
        self::assertSame($hydratorException->getArrayKey(), $key);
        self::assertIsArray($hydratorException->getPreviousThrowable());
        self::assertSame($hydratorException->getPreviousThrowable()[0], $hydratorExceptionPrevious0);
        self::assertSame($hydratorException->getPreviousThrowable()[0]->getArrayKey(), $previousKey0);
        self::assertSame($hydratorException->getPreviousThrowable()[1], $hydratorExceptionPrevious1);
        self::assertSame($hydratorException->getPreviousThrowable()[1]->getArrayKey(), $previousKey1);
        self::assertSame($mainHydratorException->getPreviousThrowable(), $hydratorException);
        self::assertNull($mainHydratorException->getArrayKey());
        self::assertStringContainsString("$key->$previousKey0: $message", $mainHydratorException->getMessage());
        self::assertStringContainsString("$key->$previousKey1: $message", $mainHydratorException->getMessage());
    }

    public function testOnePreviousExceptionOfHydratorException(): void
    {
        $previousHydratorException = new HydratorException(new \Exception($message = 'errorMessage'));
        $hydratorException = new HydratorException($previousHydratorException, $key = 'someKey');
        $mainHydratorException = new HydratorException($hydratorException);

        self::assertSame($hydratorException->getArrayKey(), $key);
        self::assertSame($mainHydratorException->getPreviousThrowable(), $hydratorException);
        self::assertNull($mainHydratorException->getArrayKey());
        self::assertStringContainsString("$key: $message", $mainHydratorException->getMessage());
    }
}
