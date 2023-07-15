<?php

declare(strict_types=1);

namespace Sav\Hydrator\Tests\Hydrator;

use Sav\Hydrator\Attribute\ArrayMap\ArrayOfObjects;
use Sav\Hydrator\Attribute\ValueValidator\NotEmpty;

final class StubClass2
{
    public function __construct(
        public readonly StubSubClass $stubSubClass,
        #[NotEmpty]
        public readonly int $a,
        #[ArrayOfObjects(StubSubClass::class)]
        public readonly array $arrayStubSubClass,
    ) {
    }
}
