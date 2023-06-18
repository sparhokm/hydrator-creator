<?php

declare(strict_types=1);

namespace Sav\Hydrator\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER | Attribute::TARGET_PROPERTY | Attribute::TARGET_CLASS)]
final class RequiredKeyValue implements \Sav\Hydrator\Attribute
{
}
