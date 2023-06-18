<?php

declare(strict_types=1);

namespace Sav\Hydrator\ConstructorParameters;

use LogicException;
use ReflectionParameter;
use Sav\Hydrator\Attribute\ValueExtractor;
use Sav\Hydrator\Attribute\ValueModifier;
use Sav\Hydrator\Attribute\ValueValidator;
use Sav\Hydrator\ConstructorParameters\Type\ClassType;

final class Parameter
{
    /**
     * @param list<ValueModifier> $valueModifiers
     * @param list<ValueValidator> $valueValidators
     */
    public function __construct(
        private readonly ReflectionParameter $parameter,
        private readonly Type $type,
        private readonly bool $isRequiredKeyValue,
        private readonly ValueExtractor $valueExtractor,
        private readonly array $valueModifiers,
        private readonly array $valueValidators,
    ) {
    }

    public function getName(): string
    {
        return $this->parameter->getName();
    }

    public function getType(): Type
    {
        return $this->type;
    }

    public function getClass(): ?string
    {
        if (!is_a($this->type, ClassType::class)) {
            return null;
        }

        return $this->type->getClass();
    }

    public function isRequiredKeyValue(): bool
    {
        return $this->isRequiredKeyValue;
    }

    public function getValueExtractor(): ValueExtractor
    {
        return $this->valueExtractor;
    }

    /**
     * @return list<ValueModifier>
     */
    public function getValueModifiers(): array
    {
        return $this->valueModifiers;
    }

    /**
     * @return list<ValueValidator>
     */
    public function getValueValidators(): array
    {
        return $this->valueValidators;
    }

    public function isDefaultValueAvailable(): bool
    {
        return $this->parameter->isDefaultValueAvailable();
    }

    /**
     * @throws \LogicException
     */
    public function getDefaultValue(): mixed
    {
        if (!$this->parameter->isDefaultValueAvailable()) {
            throw new LogicException('Default value not available');
        }

        return $this->parameter->getDefaultValue();
    }

    public function isAllowsNull(): bool
    {
        return $this->parameter->allowsNull();
    }
}
