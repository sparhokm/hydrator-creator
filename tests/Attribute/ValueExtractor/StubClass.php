<?php

declare(strict_types=1);

namespace Sav\Hydrator\Tests\Attribute\ValueExtractor;

use Sav\Hydrator\Attribute\RequiredKeyValue;
use Sav\Hydrator\Attribute\ValueExtractor\RenameFrom;

final class StubClass
{
    public function __construct(
        #[RenameFrom('oldValue')]
        public readonly int $renamed,
        public readonly ?int $notRenamed,
        #[RenameFrom('oldValue')]
        #[RequiredKeyValue]
        public readonly int $renamedRequired,
    ) {
    }
}
