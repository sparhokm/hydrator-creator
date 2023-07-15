<?php

declare(strict_types=1);

namespace Sav\Hydrator\Exception;

final class HydratorException extends \Exception
{
    /**
     * @param \Throwable|HydratorException|array<HydratorException> $previous
     */
    public function __construct(
        private readonly \Throwable|HydratorException|array $previous,
        private readonly ?string $arrayKey = null,
    ) {
        parent::__construct($this->getErrorMessage());
    }

    /**
     * @return \Throwable|HydratorException|array<HydratorException>
     */
    public function getPreviousThrowable(): \Throwable|HydratorException|array
    {
        return $this->previous;
    }

    public function getArrayKey(): ?string
    {
        return $this->arrayKey;
    }

    private function getErrorMessage(): string
    {
        $previous = $this->getPreviousThrowable();
        if (!is_array($previous)) {
            if (!is_a($previous, self::class)) {
                return $previous->getMessage();
            }

            $previous = [$previous];
        }

        return implode(PHP_EOL, $this->generateMessage($previous));
    }

    /**
     * @param array<HydratorException> $exceptions
     * @return list<string>
     */
    private function generateMessage(array $exceptions, string $previousFieldName = ''): array
    {
        $messages = [];
        foreach ($exceptions as $exception) {
            $previous = $exception->getPreviousThrowable();
            if (is_a($previous, self::class)) {
                $fieldName = $previousFieldName . (string)$exception->getArrayKey();
                $messages[] = implode(PHP_EOL, $this->generateMessage([$previous], $fieldName));
                continue;
            }

            if (is_array($previous)) {
                $fieldName = $previousFieldName;
                if ($exception->getArrayKey() !== null) {
                    $fieldName .= $exception->getArrayKey() . '->';
                }
                $messages[] = implode(PHP_EOL, $this->generateMessage($previous, $fieldName));
                continue;
            }

            $messages[] = $previousFieldName . (string)$exception->getArrayKey() . ': ' . $previous->getMessage();
        }

        return $messages;
    }
}
