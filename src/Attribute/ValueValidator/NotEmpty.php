<?php

declare(strict_types=1);

namespace Sav\Hydrator\Attribute\ValueValidator;

use Attribute;
use Sav\Hydrator\Attribute\ValueValidator;
use Sav\Hydrator\ConstructorParameters\Parameter;

#[Attribute(Attribute::TARGET_PARAMETER | Attribute::TARGET_PROPERTY | Attribute::TARGET_CLASS)]
final class NotEmpty implements ValueValidator
{
    /**
     * @throws \LogicException
     */
    public function validateValue(Parameter $parameter, mixed $value, object $context): void
    {
        if ($value === null) {
            return;
        }

        if (!empty($value)) {
            return;
        }

        if (is_bool($value)) {
            return;
        }

        throw new \LogicException('Value is empty.');
    }
}
