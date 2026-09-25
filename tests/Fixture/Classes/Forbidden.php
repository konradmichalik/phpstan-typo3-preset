<?php

declare(strict_types=1);

namespace Fixture;

use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

final class Forbidden
{
    public function debug(string $value): void
    {
        var_dump($value);
        dump($value);
        DebuggerUtility::var_dump($value);
        dd($value);
    }

    public function superglobals(): string
    {
        $values = [
            $_GET['a'] ?? '',
            $_REQUEST['b'] ?? '',
            $_COOKIE['c'] ?? '',
        ];

        return implode(',', array_map('strval', $values));
    }

    public function header(): void
    {
        header('X-Fixture: 1');
    }

    public function errorSuppression(): string|false
    {
        return @file_get_contents('/does/not/exist');
    }

    public function switchStatement(int $value): string
    {
        switch ($value) {
            case 1:
                return 'one';
            default:
                return 'other';
        }
    }

    public function emptyCheck(string $value): bool
    {
        return empty($value);
    }

    public function deprecatedCall(): string
    {
        return Legacy::greet();
    }

    public function untyped($value)
    {
        return $value;
    }
}
