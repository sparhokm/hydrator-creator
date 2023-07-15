<?php

declare(strict_types=1);

namespace Sav\Hydrator\Attribute;

use Sav\Hydrator\Attribute;
use Sav\Hydrator\ConstructorParameters\Parameter;

interface ValueModifier extends Attribute
{
    public function modifyValue(Parameter $parameter, mixed $value, object $context): mixed;
}
