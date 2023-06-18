<?php

declare(strict_types=1);

namespace Sav\Hydrator\Tests\ObjectCreator;

final class StubInternalException
{
    public function __construct()
    {
        throw new InternalException();
    }
}


final class InternalException extends \Exception
{
}
