<?php

declare(strict_types=1);

namespace Fixture;

final class IgnoreWithoutReason
{
    public function withoutReason(): string
    {
        // @phpstan-ignore staticMethod.deprecated
        return Legacy::greet();
    }

    public function withReason(): string
    {
        // @phpstan-ignore staticMethod.deprecated (kept until the legacy greeting is removed)
        return Legacy::greet();
    }
}
