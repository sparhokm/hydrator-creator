<?php

declare(strict_types=1);

namespace Sav\Hydrator\Tests\ConstructorParameters;

use Sav\Hydrator\Attribute\ArrayMap\ArrayOfObjects;
use Sav\Hydrator\Attribute\RequiredKeyValue;
use Sav\Hydrator\Attribute\ValueExtractor\Alias;
use Sav\Hydrator\Attribute\ValueModifier\DefaultValue;
use Sav\Hydrator\Attribute\ValueValidator\NotEmpty;

#[NotEmpty]
#[DefaultValue(2)]
final class StubClass
{
    public function __construct(
        public readonly array $array,
        #[ArrayOfObjects(StubClass::class)]
        public readonly array $arrayOfObjects,
        public ?string $defaultValueExtractor,
        #[Alias('boolVarOldName')]
        public string $aliasValueExtractor,
        public readonly bool $notRequired,
        #[RequiredKeyValue]
        public array $required,
        public readonly ?int $oneValidator,
        #[ValueValidatorStub]
        public readonly array $twoValidators,
        public readonly int $default2,
        #[DefaultValue(3)]
        public readonly ?int $default3,
        #[RequiredKeyValue]
        #[Alias('oldName')]
        #[ArrayOfObjects(StubClass::class)]
        #[ValueValidatorStub]
        public readonly ?array $multiplyAttributes,
        #[ArrayOfObjects(StubClass::class)]
        #[Alias('oldName')]
        public readonly ?array $context,
    ) {
    }
}
