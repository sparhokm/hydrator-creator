<?php

declare(strict_types=1);

namespace Sav\Hydrator\Tests\Attribute\ValueModifier;

use Sav\Hydrator\Attribute\ValueModifier\DefaultValue;

final class StubClass
{
    public function __construct(
        #[DefaultValue(3)]
        public readonly int $defaultForNull,
        #[DefaultValue(defaultValue: 3, applyForEmpty: true)]
        public readonly int $defaultForEmpty,
    ) {
    }
}
