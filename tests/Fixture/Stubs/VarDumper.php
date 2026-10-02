<?php

// symfony/var-dumper is not a dependency of the preset, so its helpers are stubbed for the fixture.

function dump(mixed ...$vars): mixed
{
    return null;
}

function dd(mixed ...$vars): never
{
    exit(1);
}
