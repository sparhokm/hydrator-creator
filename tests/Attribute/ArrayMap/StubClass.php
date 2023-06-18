<?php

declare(strict_types=1);

namespace Sav\Hydrator\Tests\Attribute\ArrayMap;

use Sav\Hydrator\Attribute\ArrayMap\ArrayOfObjects;

final class StubClass
{
    public function __construct(
        #[ArrayOfObjects(StubClass::class)]
        public readonly array $arrayOfObjects,
        public readonly array $array,
    ) {
    }
}
