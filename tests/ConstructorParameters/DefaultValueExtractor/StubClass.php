<?php

declare(strict_types=1);

namespace Sav\Hydrator\Tests\ConstructorParameters\DefaultValueExtractor;

use Sav\Hydrator\Attribute\RequiredKeyValue;

final class StubClass
{
    public function __construct(
        #[RequiredKeyValue]
        public readonly int $required,
        public readonly ?int $notRequired,
    ) {
    }
}
