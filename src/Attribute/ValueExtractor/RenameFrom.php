<?php

declare(strict_types=1);

namespace Sav\Hydrator\Attribute\ValueExtractor;

use Attribute;
use LogicException;
use Sav\Hydrator\Attribute\ValueExtractor;
use Sav\Hydrator\ConstructorParameters\Parameter;

#[Attribute(Attribute::TARGET_PARAMETER | Attribute::TARGET_PROPERTY)]
final class RenameFrom implements ValueExtractor
{
    public function __construct(private readonly string $oldName)
    {
    }

    /**
     * @throws LogicException
     */
    public function extractValue(Parameter $parameter, ?array $data, object $context): mixed
    {
        if (
            $parameter->isRequiredKeyValue()
            && ($data === null || !array_key_exists($this->oldName, $data))
        ) {
            throw new LogicException('Value not exist.');
        }

        return $data[$this->oldName] ?? null;
    }
}
