<?php

declare(strict_types=1);

namespace Sav\Hydrator\Tests\ConstructorParameters;

final class StubClassException
{
    public function __construct(
        public string|int $unionType,
    ) {
    }
}
