<?php

declare(strict_types=1);

namespace Sav\Hydrator\Tests\ConstructorParameters\DefaultValueExtractor;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Sav\Hydrator\ConstructorParameters\Parameter;
use Sav\Hydrator\ConstructorParameters\ConstructorParameters;
use Sav\Hydrator\ConstructorParameters\DefaultValueExtractor;
use Sav\Hydrator\ConstructorParameters\TypeFactory;
use Sav\Hydrator\ReflectionClassFactory;
use Sav\Hydrator\Tests\ConstructorParametersHelper;

#[CoversClass(DefaultValueExtractor::class)]
final class DefaultValueExtractorTest extends TestCase
{
    public function testExtractValue(): void
    {
        $parameters = ConstructorParametersHelper::getParameters(StubClass::class);
        $defaultValueExtractor = new DefaultValueExtractor();
        $context = new \stdClass();

        $required = ConstructorParametersHelper::getParameterByName($parameters, 'required');
        $notRequired = ConstructorParametersHelper::getParameterByName($parameters, 'notRequired');

        self::assertSame(2, $defaultValueExtractor->extractValue($required, ['required' => 2], $context));
        self::assertSame(2, $defaultValueExtractor->extractValue($notRequired, ['notRequired' => 2], $context));
    }

    public function testExtractValueWithNotExistKeyValue(): void
    {
        $parameters = ConstructorParametersHelper::getParameters(StubClass::class);
        $defaultValueExtractor = new DefaultValueExtractor();
        $context = new \stdClass();
        self::expectException(\LogicException::class);

        $required = ConstructorParametersHelper::getParameterByName($parameters, 'required');
        $notRequired = ConstructorParametersHelper::getParameterByName($parameters, 'notRequired');

        self::assertNull($defaultValueExtractor->extractValue($notRequired, ['other' => 2], $context));
        $defaultValueExtractor->extractValue($required, ['other' => 2], $context);
    }
}
