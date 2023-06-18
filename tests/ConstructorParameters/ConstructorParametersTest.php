<?php

declare(strict_types=1);

namespace Sav\Hydrator\Tests\ConstructorParameters;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sav\Hydrator\Attribute\ValueExtractor\RenameFrom;
use Sav\Hydrator\Attribute\ValueModifier\DefaultValue;
use Sav\Hydrator\Attribute\ValueValidator\NotEmpty;
use Sav\Hydrator\ConstructorParameters\Parameter;
use Sav\Hydrator\ConstructorParameters\ConstructorParameters;
use Sav\Hydrator\ConstructorParameters\DefaultValueExtractor;
use Sav\Hydrator\ConstructorParameters\Type\ArrayMapType;
use Sav\Hydrator\ConstructorParameters\Type\ArrayType;
use Sav\Hydrator\ConstructorParameters\TypeFactory;
use Sav\Hydrator\ReflectionClassFactory;
use Sav\Hydrator\Tests\ConstructorParametersHelper;

#[CoversClass(ConstructorParameters::class)]
#[CoversClass(Parameter::class)]
final class ConstructorParametersTest extends TestCase
{
    public function testValueExtractor(): void
    {
        $parameters = ConstructorParametersHelper::getParameters(StubClass::class);

        $defaultValueExtractor = ConstructorParametersHelper::getParameterByName($parameters, 'defaultValueExtractor');
        $renameFromValueExtractor = ConstructorParametersHelper::getParameterByName($parameters, 'renameFromValueExtractor');

        self::assertInstanceOf(DefaultValueExtractor::class, $defaultValueExtractor->getValueExtractor());
        self::assertInstanceOf(RenameFrom::class, $renameFromValueExtractor->getValueExtractor());
    }

    public function testRequiredKeyValue(): void
    {
        $parameters = ConstructorParametersHelper::getParameters(StubClass::class);

        $notRequired = ConstructorParametersHelper::getParameterByName($parameters, 'notRequired');
        $required = ConstructorParametersHelper::getParameterByName($parameters, 'required');

        self::assertFalse($notRequired->isRequiredKeyValue());
        self::assertTrue($required->isRequiredKeyValue());
    }

    public function testValueValidator(): void
    {
        $parameters = ConstructorParametersHelper::getParameters(StubClass::class);

        $oneValidator = ConstructorParametersHelper::getParameterByName($parameters, 'oneValidator');
        $twoValidators = ConstructorParametersHelper::getParameterByName($parameters, 'twoValidators');

        self::assertCount(1, $oneValidator->getValueValidators());
        self::assertCount(2, $twoValidators->getValueValidators());
        self::assertInstanceOf(NotEmpty::class, $twoValidators->getValueValidators()[0]);
        self::assertInstanceOf(ValueValidatorStub::class, $twoValidators->getValueValidators()[1]);
    }

    public function testOverwriteExistedValueModifier(): void
    {
        $parameters = ConstructorParametersHelper::getParameters(StubClass::class);

        $default2 = ConstructorParametersHelper::getParameterByName($parameters, 'default2');
        $default3 = ConstructorParametersHelper::getParameterByName($parameters, 'default3');

        self::assertCount(1, $default2->getValueModifiers());
        self::assertCount(1, $default3->getValueModifiers());
        self::assertInstanceOf(DefaultValue::class, $default2->getValueModifiers()[0]);
        self::assertInstanceOf(DefaultValue::class, $default3->getValueModifiers()[0]);
        self::assertEquals(2, $default2->getValueModifiers()[0]->modifyValue($default2, null, new \stdClass()));
        self::assertEquals(3, $default3->getValueModifiers()[0]->modifyValue($default3, null, new \stdClass()));
    }

    public function testArrayMap(): void
    {
        $parameters = ConstructorParametersHelper::getParameters(StubClass::class);

        $arrayMapType = ConstructorParametersHelper::getParameterByName($parameters, 'arrayOfObjects')->getType();
        $arrayType = ConstructorParametersHelper::getParameterByName($parameters, 'array')->getType();

        self::assertInstanceOf(ArrayType::class, $arrayType);
        self::assertInstanceOf(ArrayMapType::class, $arrayMapType);
    }

    public function testReturnAttributesFromMemoize(): void
    {
        $classConstructorParameters = new ConstructorParameters(new ReflectionClassFactory(), new TypeFactory());
        $parameters = $classConstructorParameters->getParameters(StubClass::class);

        $parametersExisted = $classConstructorParameters->getParameters(StubClass::class);
        $parametersNotExisted = $classConstructorParameters->getParameters(ValueModifierStub::class);

        self::assertSame($parameters[0], $parametersExisted[0]);
        self::assertNotSame($parameters[0], $parametersNotExisted[0]);
    }

    public function testMultiplyAttributes(): void
    {
        $parameters = ConstructorParametersHelper::getParameters(StubClass::class);

        $multiplyAttributes = ConstructorParametersHelper::getParameterByName($parameters, 'multiplyAttributes');

        self::assertCount(1, $multiplyAttributes->getValueModifiers());
        self::assertCount(2, $multiplyAttributes->getValueValidators());
        self::assertTrue($multiplyAttributes->isRequiredKeyValue());
        self::assertInstanceOf(RenameFrom::class, $multiplyAttributes->getValueExtractor());
    }

    public function testThrowOnUnionTypeAttribute(): void
    {
        self::expectException(\LogicException::class);

        ConstructorParametersHelper::getParameters(StubClassException::class);
    }
}
