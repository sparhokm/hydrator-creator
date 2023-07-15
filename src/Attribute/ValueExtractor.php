<?php

declare(strict_types=1);

namespace Sav\Hydrator\Attribute;

use Sav\Hydrator\Attribute;
use Sav\Hydrator\ConstructorParameters\Parameter;

interface ValueExtractor extends Attribute
{
    public function extractValue(Parameter $parameter, array $data, object $context): mixed;
}
