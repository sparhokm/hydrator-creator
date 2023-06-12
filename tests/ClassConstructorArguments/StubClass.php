<?php

declare(strict_types=1);

namespace Sav\Hydrator\ClassConstructorArguments;

/**
 * @psalm-suppress PossiblyUnusedProperty
 */
final class StubClass
{
    public function __construct(
        public readonly bool $boolVar,
        public readonly \DateTimeImmutable $dateTimeImmutable,
        public readonly object $objectVar,
        public readonly array $arrayVar,
    ) {
    }
}
