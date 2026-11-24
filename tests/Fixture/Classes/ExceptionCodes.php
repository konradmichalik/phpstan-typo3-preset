<?php

declare(strict_types=1);

namespace Fixture;

final class ExceptionCodes
{
    public function missing(): never
    {
        throw new \RuntimeException('no code');
    }

    public function first(): never
    {
        throw new \RuntimeException('first', 1726357767);
    }

    public function second(): never
    {
        throw new \RuntimeException('second', 1726357767);
    }

    public function unique(): never
    {
        throw new \RuntimeException('unique', 1726357768);
    }
}
