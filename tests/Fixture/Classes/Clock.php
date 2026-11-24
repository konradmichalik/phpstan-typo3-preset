<?php

declare(strict_types=1);

namespace Fixture;

use TYPO3\CMS\Core\Context\Context;

final class Clock
{
    public function __construct(private readonly Context $context) {}

    public function now(): int
    {
        return time();
    }

    public function today(): string
    {
        return date('Y-m-d');
    }

    public function formatted(int $timestamp): string
    {
        return date('Y-m-d', $timestamp);
    }

    public function immutable(): \DateTimeImmutable
    {
        return new \DateTimeImmutable();
    }

    public function immutableNow(): \DateTimeImmutable
    {
        return new \DateTimeImmutable('now');
    }

    public function immutableFixed(): \DateTimeImmutable
    {
        return new \DateTimeImmutable('2026-01-01');
    }

    public function mutable(): \DateTime
    {
        return new \DateTime();
    }

    public function aspect(): int
    {
        $timestamp = $this->context->getPropertyFromAspect('date', 'timestamp');

        return is_int($timestamp) ? $timestamp : 0;
    }
}
