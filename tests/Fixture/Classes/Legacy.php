<?php

declare(strict_types=1);

namespace Fixture;

final class Legacy
{
    /**
     * @deprecated use Allowed::greet() instead
     */
    public static function greet(): string
    {
        return 'Hello';
    }
}
