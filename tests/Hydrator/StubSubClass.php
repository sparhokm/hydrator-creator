<?php

declare(strict_types=1);

namespace Sav\Hydrator\Tests\Hydrator;

final class StubSubClass
{
    public function __construct(
        public readonly \DateTimeImmutable $dateTimeImmutable,
        public readonly int $intWithDefault = 2
    ) {
    }
}
