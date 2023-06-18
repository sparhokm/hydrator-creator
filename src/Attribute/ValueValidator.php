<?php

declare(strict_types=1);

namespace Sav\Hydrator\Attribute;

use Sav\Hydrator\Attribute;
use Sav\Hydrator\ConstructorParameters\Parameter;

interface ValueValidator extends Attribute
{
    public function validateValue(Parameter $parameter, mixed $value, object $context): void;
}
