<?php

declare(strict_types=1);

namespace Sav\Hydrator\Tests\Attribute\ValueExtractor;

use Sav\Hydrator\Attribute\RequiredKeyValue;
use Sav\Hydrator\Attribute\ValueExtractor\Alias;

final class StubClass
{
    public function __construct(
        #[Alias('oldValue')]
        public readonly int $alias,
        public readonly ?int $notAlias,
        #[Alias('oldValue')]
        #[RequiredKeyValue]
        public readonly int $aliasRequired,
    ) {
    }
}
