<?php

declare(strict_types=1);

namespace Sav\Hydrator\Tests\Attribute\ValueExtractor;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sav\Hydrator\Attribute\ValueExtractor\Alias;
use Sav\Hydrator\Tests\ConstructorParametersHelper;

#[CoversClass(Alias::class)]
final class AliasTest extends TestCase
{
    public function testAlias(): void
    {
        $parameters = ConstructorParametersHelper::getParameters(StubClass::class);
        $context = new \stdClass();

        $alias = ConstructorParametersHelper::getParameterByName($parameters, 'alias');
        $notAlias = ConstructorParametersHelper::getParameterByName($parameters, 'notAlias');

        self::assertSame(2, $alias->getValueExtractor()->extractValue($alias, ['oldValue' => 2], $context));
        self::assertSame(3, $notAlias->getValueExtractor()->extractValue($notAlias, ['notAlias' => 3], $context));
        self::assertNull($alias->getValueExtractor()->extractValue($notAlias, ['other' => 3], $context));
    }

    public function testTryGetAliasByNewName(): void
    {
        $parameters = ConstructorParametersHelper::getParameters(StubClass::class);
        $context = new \stdClass();

        $alias = ConstructorParametersHelper::getParameterByName($parameters, 'alias');

        self::assertEquals(2, $alias->getValueExtractor()->extractValue($alias, ['alias' => 2], $context));
    }

    public function testTryGetRequiredByMissName(): void
    {
        $parameters = ConstructorParametersHelper::getParameters(StubClass::class);
        $context = new \stdClass();
        self::expectException(\LogicException::class);

        $renamedRequired = ConstructorParametersHelper::getParameterByName($parameters, 'aliasRequired');

        $renamedRequired->getValueExtractor()->extractValue($renamedRequired, ['missName' => 2], $context);
    }
}
