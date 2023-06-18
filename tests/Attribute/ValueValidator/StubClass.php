<?php

declare(strict_types=1);

namespace Sav\Hydrator\Tests\Attribute\ValueValidator;

use Sav\Hydrator\Attribute\ValueValidator\NotEmpty;

final class StubClass
{
    public function __construct(
        #[NotEmpty]
        public readonly ?int $notEmpty,
        #[NotEmpty]
        public readonly ?bool $notEmptyBool,
    ) {
    }
}
