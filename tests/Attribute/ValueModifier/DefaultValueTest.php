<?php

declare(strict_types=1);

namespace Sav\Hydrator\Tests\Attribute\ValueModifier;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sav\Hydrator\Attribute\ValueModifier\DefaultValue;
use Sav\Hydrator\Tests\ConstructorParametersHelper;

#[CoversClass(DefaultValue::class)]
final class DefaultValueTest extends TestCase
{
    public function testReturnDefaultValueForNull(): void
    {
        $parameters = ConstructorParametersHelper::getParameters(StubClass::class);
        $context = new \stdClass();

        $defaultForNull = ConstructorParametersHelper::getParameterByName($parameters, 'defaultForNull');

        self::assertSame(3, $defaultForNull->getValueModifiers()[0]->modifyValue($defaultForNull, null, $context));
        self::assertSame(0, $defaultForNull->getValueModifiers()[0]->modifyValue($defaultForNull, 0, $context));
        self::assertSame(2, $defaultForNull->getValueModifiers()[0]->modifyValue($defaultForNull, 2, $context));
    }

    public function testReturnDefaultValueForEmpty(): void
    {
        $parameters = ConstructorParametersHelper::getParameters(StubClass::class);
        $context = new \stdClass();

        $haveDefaultValue = ConstructorParametersHelper::getParameterByName($parameters, 'defaultForEmpty');

        self::assertSame(3, $haveDefaultValue->getValueModifiers()[0]->modifyValue($haveDefaultValue, null, $context));
        self::assertSame(3, $haveDefaultValue->getValueModifiers()[0]->modifyValue($haveDefaultValue, 0, $context));
        self::assertSame(2, $haveDefaultValue->getValueModifiers()[0]->modifyValue($haveDefaultValue, 2, $context));
    }
}
