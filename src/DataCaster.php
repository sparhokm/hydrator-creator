<?php

declare(strict_types=1);

namespace Sav\Hydrator;

use InvalidArgumentException;
use LogicException;
use Sav\Hydrator\ConstructorParameters\Parameter;

/** @psalm-suppress MixedAssignment */
final class DataCaster
{
    /**
     * @param array $data
     * @throws LogicException
     */
    public function cast(Parameter $parameter, array $data, object $context): mixed
    {
        $value = $this->extractValue($parameter, $data, $context);

        $value = $parameter->getType()->cast($value);

        $value = $this->modifyValue($parameter, $value, $context);

        $this->validateValue($parameter, $value, $context);

        if ($value === null && !$parameter->isAllowsNull()) {
            throw new LogicException('Value is null.');
        }

        return $value;
    }

    /**
     * @throws InvalidArgumentException
     */
    private function extractValue(Parameter $parameter, array $data, object $context): mixed
    {
        return $parameter->getValueExtractor()->extractValue($parameter, $data, $context);
    }

    private function modifyValue(Parameter $parameter, mixed $value, object $context): mixed
    {
        foreach ($parameter->getValueModifiers() as $valueModifier) {
            $value = $valueModifier->modifyValue($parameter, $value, $context);
        }

        return $value;
    }

    private function validateValue(Parameter $parameter, mixed $value, object $context): mixed
    {
        foreach ($parameter->getValueValidators() as $valueValidator) {
            $valueValidator->validateValue($parameter, $value, $context);
        }

        return $value;
    }
}
