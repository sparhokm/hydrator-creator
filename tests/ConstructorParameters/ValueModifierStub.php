<?php

declare(strict_types=1);

namespace Sav\Hydrator\Tests\ConstructorParameters;

use Attribute;
use Sav\Hydrator\Attribute\ValueModifier;
use Sav\Hydrator\ConstructorParameters\Parameter;

#[Attribute(Attribute::TARGET_PARAMETER | Attribute::TARGET_PROPERTY | Attribute::TARGET_CLASS)]
final class ValueModifierStub implements ValueModifier
{
    public function __construct(private readonly string $someVal)
    {
    }

    public function modifyValue(Parameter $parameter, mixed $value, object $context): mixed
    {
        return $this->someVal;
    }
}
