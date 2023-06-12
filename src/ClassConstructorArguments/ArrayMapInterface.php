<?php

declare(strict_types=1);

namespace Sav\Hydrator\ClassConstructorArguments;

interface ArrayMapInterface
{
    public function getKeyType(mixed $arrayKey, object $context): TypeInterface;
}
