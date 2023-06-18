<?php

declare(strict_types=1);

namespace Sav\Hydrator\Tests\Attribute\ValueValidator;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sav\Hydrator\Attribute\ValueValidator\NotEmpty;
use Sav\Hydrator\Tests\ConstructorParametersHelper;

#[CoversClass(NotEmpty::class)]
final class NotEmptyTest extends TestCase
{
    public function testValidateNotEmptyOrNull(): void
    {
        $parameters = ConstructorParametersHelper::getParameters(StubClass::class);
        $context = new \stdClass();
        self::expectNotToPerformAssertions();

        $notEmpty = ConstructorParametersHelper::getParameterByName($parameters, 'notEmpty');

        $notEmpty->getValueValidators()[0]->validateValue($notEmpty, null, $context);
        $notEmpty->getValueValidators()[0]->validateValue($notEmpty, 1, $context);
    }

    public function testValidateEmpty(): void
    {
        $parameters = ConstructorParametersHelper::getParameters(StubClass::class);
        $context = new \stdClass();
        self::expectException(\LogicException::class);

        $notEmpty = ConstructorParametersHelper::getParameterByName($parameters, 'notEmpty');

        $notEmpty->getValueValidators()[0]->validateValue($notEmpty, 0, $context);
    }

    public function testValidateBool(): void
    {
        $parameters = ConstructorParametersHelper::getParameters(StubClass::class);
        $context = new \stdClass();
        self::expectNotToPerformAssertions();

        $notEmptyBool = ConstructorParametersHelper::getParameterByName($parameters, 'notEmptyBool');

        $notEmptyBool->getValueValidators()[0]->validateValue($notEmptyBool, true, $context);
        $notEmptyBool->getValueValidators()[0]->validateValue($notEmptyBool, false, $context);
        $notEmptyBool->getValueValidators()[0]->validateValue($notEmptyBool, null, $context);
    }
}
