<?php

declare(strict_types=1);

namespace Sav\Hydrator\Tests\Attribute\ValueExtractor;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sav\Hydrator\Attribute\ValueExtractor\RenameFrom;
use Sav\Hydrator\Tests\ConstructorParametersHelper;

#[CoversClass(RenameFrom::class)]
final class RenameFromTest extends TestCase
{
    public function testRename(): void
    {
        $parameters = ConstructorParametersHelper::getParameters(StubClass::class);
        $context = new \stdClass();

        $renamed = ConstructorParametersHelper::getParameterByName($parameters, 'renamed');
        $notRenamed = ConstructorParametersHelper::getParameterByName($parameters, 'notRenamed');

        self::assertSame(2, $renamed->getValueExtractor()->extractValue($renamed, ['oldValue' => 2], $context));
        self::assertSame(3, $notRenamed->getValueExtractor()->extractValue($notRenamed, ['notRenamed' => 3], $context));
        self::assertNull($renamed->getValueExtractor()->extractValue($notRenamed, ['other' => 3], $context));
    }

    public function testTryGetRenamedByNewName(): void
    {
        $parameters = ConstructorParametersHelper::getParameters(StubClass::class);
        $context = new \stdClass();

        $renamed = ConstructorParametersHelper::getParameterByName($parameters, 'renamed');

        self::assertEquals(2, $renamed->getValueExtractor()->extractValue($renamed, ['renamed' => 2], $context));
    }

    public function testTryGetRequiredByMissName(): void
    {
        $parameters = ConstructorParametersHelper::getParameters(StubClass::class);
        $context = new \stdClass();
        self::expectException(\LogicException::class);

        $renamedRequired = ConstructorParametersHelper::getParameterByName($parameters, 'renamedRequired');

        $renamedRequired->getValueExtractor()->extractValue($renamedRequired, ['missName' => 2], $context);
    }
}
