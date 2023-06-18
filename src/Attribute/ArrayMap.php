<?php

declare(strict_types=1);

namespace Sav\Hydrator\Attribute;

use Sav\Hydrator\Attribute;
use Sav\Hydrator\ConstructorParameters\Type;

interface ArrayMap extends Attribute
{
    public function getKeyType(mixed $arrayKey, object $context): Type;
}
