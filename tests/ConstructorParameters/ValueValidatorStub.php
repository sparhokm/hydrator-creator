<?php

declare(strict_types=1);

namespace Sav\Hydrator\Tests\ConstructorParameters;

use Attribute;
use Sav\Hydrator\Attribute\ValueValidator;
use Sav\Hydrator\ConstructorParameters\Parameter;

#[Attribute(Attribute::TARGET_PARAMETER | Attribute::TARGET_PROPERTY | Attribute::TARGET_CLASS)]
final class ValueValidatorStub implements ValueValidator
{
    public function validateValue(Parameter $parameter, mixed $value, object $context): void
    {
    }
}
