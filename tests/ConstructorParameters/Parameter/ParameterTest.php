<?php

declare(strict_types=1);

namespace Sav\Hydrator\Tests\ConstructorParameters\Parameter;

use PHPUnit\Framework\TestCase;
use Sav\Hydrator\ConstructorParameters\Parameter;
use Sav\Hydrator\ConstructorParameters\ConstructorParameters;
use Sav\Hydrator\ConstructorParameters\TypeFactory;
use Sav\Hydrator\ReflectionClassFactory;
use Sav\Hydrator\Tests\ConstructorParametersHelper;

final class ParameterTest extends TestCase
{
    public function testNullableParameter(): void
    {
        $parameters = ConstructorParametersHelper::getParameters(StubClass::class);

        $nullable = ConstructorParametersHelper::getParameterByName($parameters, 'nullable');
        $notNullable = ConstructorParametersHelper::getParameterByName($parameters, 'notNullable');

        self::assertTrue($nullable->isAllowsNull());
        self::assertFalse($notNullable->isAllowsNull());
    }

    public function testParameterHaveNotDefaultValue(): void
    {
        $parameters = ConstructorParametersHelper::getParameters(StubClass::class);
        self::expectException(\LogicException::class);

        $noDefaultValue = ConstructorParametersHelper::getParameterByName($parameters, 'noDefaultValue');

        self::assertTrue(!$noDefaultValue->isDefaultValueAvailable());
        $noDefaultValue->getDefaultValue();
    }

    public function testParameterHaveDefaultValue(): void
    {
        $parameters = ConstructorParametersHelper::getParameters(StubClass::class);

        $haveDefaultValue = ConstructorParametersHelper::getParameterByName($parameters, 'haveDefaultValue');

        self::assertTrue($haveDefaultValue->isDefaultValueAvailable());
        self::assertSame(2, $haveDefaultValue->getDefaultValue());
    }

    public function testParameterHaveClass(): void
    {
        $parameters = ConstructorParametersHelper::getParameters(StubClass::class);

        $class = ConstructorParametersHelper::getParameterByName($parameters, 'class');
        $noClass = ConstructorParametersHelper::getParameterByName($parameters, 'noClass');

        self::assertSame($class->getClass(), \DateTimeImmutable::class);
        self::assertNull($noClass->getClass());
    }
}
