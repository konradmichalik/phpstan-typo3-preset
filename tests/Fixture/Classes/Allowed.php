<?php

declare(strict_types=1);

namespace Fixture;

use Psr\Http\Message\ServerRequestInterface;

final class Allowed
{
    public function __construct(private readonly string $name) {}

    public function greet(ServerRequestInterface $request): string
    {
        $query = $request->getQueryParams();
        $suffix = is_string($query['suffix'] ?? null) ? $query['suffix'] : '';

        return sprintf('Hello %s%s', $this->name, $suffix);
    }
}
