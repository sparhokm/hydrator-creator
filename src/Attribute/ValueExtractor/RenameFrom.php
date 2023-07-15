<?php

declare(strict_types=1);

namespace Sav\Hydrator\Attribute\ValueExtractor;

use Attribute;
use LogicException;
use Sav\Hydrator\Attribute\ValueExtractor;
use Sav\Hydrator\ConstructorParameters\DefaultValueExtractor;
use Sav\Hydrator\ConstructorParameters\Parameter;

#[Attribute(Attribute::TARGET_PARAMETER | Attribute::TARGET_PROPERTY)]
final class RenameFrom implements ValueExtractor
{
    private readonly DefaultValueExtractor $defaultValueExtractor;

    public function __construct(private readonly string $oldName)
    {
        $this->defaultValueExtractor = new DefaultValueExtractor();
    }

    /**
     * @throws LogicException
     */
    public function extractValue(Parameter $parameter, array $data, object $context): mixed
    {
        if ($data && array_key_exists($this->oldName, $data)) {
            /** @psalm-suppress MixedAssignment */
            $data[$parameter->getName()] = $data[$this->oldName];
        }

        return $this->defaultValueExtractor->extractValue($parameter, $data, $context);
    }
}
