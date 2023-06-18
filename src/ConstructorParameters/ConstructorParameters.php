<?php

declare(strict_types=1);

namespace Sav\Hydrator\ConstructorParameters;

use LogicException;
use ReflectionAttribute;
use ReflectionException;
use ReflectionParameter;
use Sav\Hydrator\Attribute;
use Sav\Hydrator\Attribute\ArrayMap;
use Sav\Hydrator\Attribute\RequiredKeyValue;
use Sav\Hydrator\Attribute\ValueExtractor;
use Sav\Hydrator\Attribute\ValueModifier;
use Sav\Hydrator\Attribute\ValueValidator;
use Sav\Hydrator\ReflectionClassFactory;

/**
 * @psalm-type attributes = array{
 *     arrayMap: ?ArrayMap,
 *     isRequiredKeyValue: ?bool,
 *     valueExtractor: ?ValueExtractor,
 *     valueModifiers: array<class-string, ValueModifier>,
 *     valueValidators: array<class-string, ValueValidator>
 * }
 */
final class ConstructorParameters
{
    /** @var array<class-string, list<Parameter>> */
    private array $loadedClasses = [];

    public function __construct(
        private readonly ReflectionClassFactory $reflectionClass,
        private readonly TypeFactory $typeFactory,
    ) {
    }

    /**
     * @param class-string $class
     * @return list<Parameter>
     * @throws ReflectionException
     * @throws LogicException
     */
    public function getParameters(string $class): array
    {
        if (array_key_exists($class, $this->loadedClasses)) {
            return $this->loadedClasses[$class];
        }

        $reflectionClass = $this->reflectionClass->get($class);

        $parameters = [];
        foreach ($reflectionClass->getConstructor()?->getParameters() ?? [] as $refParameter) {
            $classAttributes = $this->extractAttributes(
                $reflectionClass->getAttributes(
                    Attribute::class,
                    ReflectionAttribute::IS_INSTANCEOF,
                )
            );
            $parameters[] = $this->getParameter($refParameter, $classAttributes);
        }

        $this->loadedClasses[$class] = $parameters;

        return $parameters;
    }

    /**
     * @param attributes $classAttributes
     * @throws LogicException
     */
    private function getParameter(ReflectionParameter $parameter, array $classAttributes): Parameter
    {
        $type = $parameter->getType();
        if (
            $type !== null
            && !$type instanceof \ReflectionNamedType
        ) {
            throw new LogicException('Union and intersection types not supported.');
        }

        $attributes = $this->extractAttributes(
            $parameter->getAttributes(
                Attribute::class,
                ReflectionAttribute::IS_INSTANCEOF,
            )
        );

        return new Parameter(
            parameter: $parameter,
            type: $this->typeFactory->get($type, $attributes['arrayMap'] ?? $classAttributes['arrayMap']),
            isRequiredKeyValue: $attributes['isRequiredKeyValue'] ?? $classAttributes['isRequiredKeyValue'] ?? false,
            valueExtractor: $attributes['valueExtractor'] ?? $classAttributes['valueExtractor'] ?? new DefaultValueExtractor(),
            valueModifiers: array_values(array_merge($classAttributes['valueModifiers'], $attributes['valueModifiers'])),
            valueValidators: array_values(array_merge($classAttributes['valueValidators'], $attributes['valueValidators'])),
        );
    }

    /**
     * @param array<ReflectionAttribute<Attribute>> $attributes
     * @return attributes
     */
    private function extractAttributes(array $attributes): array
    {
        $extractedAttributes = [
            'arrayMap' => null,
            'isRequiredKeyValue' => null,
            'valueExtractor' => null,
            'valueModifiers' => [],
            'valueValidators' => [],
        ];

        foreach ($attributes as $attribute) {
            $attributeInstance = $attribute->newInstance();

            if (is_subclass_of($attributeInstance, ArrayMap::class)) {
                $extractedAttributes['arrayMap'] = $attributeInstance;
            }

            if (is_a($attributeInstance, RequiredKeyValue::class)) {
                $extractedAttributes['isRequiredKeyValue'] = true;
            }

            if (is_subclass_of($attributeInstance, ValueExtractor::class)) {
                $extractedAttributes['valueExtractor'] = $attributeInstance;
            }

            if (is_subclass_of($attributeInstance, ValueModifier::class)) {
                $extractedAttributes['valueModifiers'][$attributeInstance::class] = $attributeInstance;
            }

            if (is_subclass_of($attributeInstance, ValueValidator::class)) {
                $extractedAttributes['valueValidators'][$attributeInstance::class] = $attributeInstance;
            }
        }

        return $extractedAttributes;
    }
}
