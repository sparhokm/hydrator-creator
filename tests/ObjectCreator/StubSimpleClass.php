<?php

declare(strict_types=1);

namespace Sav\Hydrator\ObjectCreator;

final class StubSimpleClass
{
    public function __construct(
        public readonly string $string,
        public readonly int $int,
        public readonly bool $bool,
        public readonly \DateTimeImmutable $dateTimeImmutable,
    ) {
    }
}
