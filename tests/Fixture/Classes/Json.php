<?php

declare(strict_types=1);

namespace Fixture;

final class Json
{
    public function withoutFlag(string $json): mixed
    {
        return json_decode($json, true);
    }

    public function withFlag(string $json): mixed
    {
        return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    }

    public function withCombinedFlags(string $json): mixed
    {
        return json_decode($json, true, 512, JSON_BIGINT_AS_STRING | JSON_THROW_ON_ERROR);
    }
}
