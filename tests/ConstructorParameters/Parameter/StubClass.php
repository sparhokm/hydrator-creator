<?php

declare(strict_types=1);

namespace Sav\Hydrator\Tests\ConstructorParameters\Parameter;

final class StubClass
{
    public function __construct(
        public readonly ?int $nullable,
        public readonly int $notNullable,
        public readonly string $noClass,
        public readonly \DateTimeImmutable $class,
        public readonly int $noDefaultValue,
        public readonly int $haveDefaultValue = 2,
    ) {
    }
}
