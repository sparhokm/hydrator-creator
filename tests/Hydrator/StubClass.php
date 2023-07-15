<?php

declare(strict_types=1);

namespace Sav\Hydrator\Tests\Hydrator;

use Sav\Hydrator\Attribute\ArrayMap\ArrayOfObjects;
use Sav\Hydrator\Attribute\ValueExtractor\RenameFrom;
use Sav\Hydrator\Attribute\ValueModifier\DefaultValue;
use Sav\Hydrator\Attribute\ValueValidator\NotEmpty;

final class StubClass
{
    /**
     * @param list<StubSubClass> $arrayStubSubClass
     */
    public function __construct(
        public readonly int $int,
        public readonly float $float,
        #[NotEmpty]
        public readonly string $string,
        public readonly bool $bool,
        #[DefaultValue(5)]
        public readonly int $defaultInt,
        public readonly ?string $nullString,
        #[RenameFrom('oldStubSubClass')]
        public readonly StubSubClass $stubSubClass,
        public readonly ?StubSubClass $nullStubSubClass,
        public readonly StubSubClass $createdStubSubClass,
        #[ArrayOfObjects(StubSubClass::class)]
        public readonly array $arrayStubSubClass,
        public readonly string $stringWithDefault = 'a'
    ) {
    }
}
