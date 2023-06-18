<?php

declare(strict_types=1);

namespace Sav\Hydrator\Attribute\ValueModifier;

use Attribute;
use Sav\Hydrator\Attribute\ValueModifier;
use Sav\Hydrator\ConstructorParameters\Parameter;

#[Attribute(Attribute::TARGET_PARAMETER | Attribute::TARGET_PROPERTY | Attribute::TARGET_CLASS)]
final class DefaultValue implements ValueModifier
{
    public function __construct(
        private readonly mixed $defaultValue,
        private readonly bool $applyForEmpty = false,
    ) {
    }

    public function modifyValue(Parameter $parameter, mixed $value, object $context): mixed
    {
        if ($value === null) {
            return $this->defaultValue;
        }
        if (!$this->applyForEmpty) {
            return $value;
        }
        if (!empty($value)) {
            return $value;
        }

        return $this->defaultValue;
    }
}
