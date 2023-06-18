<?php

declare(strict_types=1);

namespace Sav\Hydrator\ConstructorParameters;

use LogicException;
use Sav\Hydrator\Attribute\ValueExtractor;

final class DefaultValueExtractor implements ValueExtractor
{
    /**
     * @throws LogicException
     */
    public function extractValue(Parameter $parameter, ?array $data, object $context): mixed
    {
        if (
            $parameter->isRequiredKeyValue()
            && ($data === null || !array_key_exists($parameter->getName(), $data))
        ) {
            throw new LogicException('Value not exist.');
        }

        return $data[$parameter->getName()] ?? null;
    }
}
