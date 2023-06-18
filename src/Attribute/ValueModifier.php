<?php

declare(strict_types=1);

namespace Sav\Hydrator\Attribute;

use Sav\Hydrator\Attribute;
use Sav\Hydrator\ConstructorParameters\Parameter;

interface ValueModifier extends Attribute
{
    /**
     * @template T
     * @param T $value
     * @return T|null
     */
    public function modifyValue(Parameter $parameter, mixed $value, object $context): mixed;
}
