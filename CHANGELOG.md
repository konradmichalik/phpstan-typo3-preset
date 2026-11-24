# Changelog

## Unreleased

### Upgrade notes

- **Exception codes are checked** ([#41](https://github.com/konradmichalik/phpstan-typo3-preset/issues/41)). `konradmichalik/phpstan-exception-codes` is now required. `throw new X(...)` without a code reports `exceptionCode.missing`, a code outside `1000000000` to `2147483647` reports `exceptionCode.outOfRange` and a code thrown in more than one place reports `exceptionCode.duplicate`. Fix the codes, or move the entries to the baseline. The range can be changed with `parameters.exceptionCodes.minimum` and `maximum`
- **Inline ignores require a reason** ([#44](https://github.com/konradmichalik/phpstan-typo3-preset/issues/44)). `reportIgnoresWithoutComments` is enabled, so `@phpstan-ignore <identifier>` must become `@phpstan-ignore <identifier> (reason)`. Replace `@phpstan-ignore-line` and `@phpstan-ignore-next-line` with an identifier and a reason, or move the entries to the baseline
- **System clock reads are disallowed** ([#42](https://github.com/konradmichalik/phpstan-typo3-preset/issues/42)). `time()`, `date()` without a timestamp and `new DateTimeImmutable()` without argument or with `'now'` report `disallowed.function` or `disallowed.new`. Read the date aspect instead (`$context->getPropertyFromAspect('date', 'timestamp')` or `'full'`), which `#[FreezeTime]` from `konradmichalik/ttt` can pin in tests. Code that cannot use the aspect, such as CLI-only code, goes into the baseline
- **`json_decode()` requires `JSON_THROW_ON_ERROR`** ([#43](https://github.com/konradmichalik/phpstan-typo3-preset/issues/43)). Calls without the flag report `disallowed.function`. Pass `JSON_THROW_ON_ERROR` as the fourth argument, or move the entries to the baseline

## 0.6.1

### Upgrade notes

- **`ergebnis.testCaseWithSuffix` is no longer enabled** ([#38](https://github.com/konradmichalik/phpstan-typo3-preset/issues/38)). It crashes PHPStan in projects without `phpunit/phpunit`. Projects with PHPUnit enable it in their own configuration with `parameters.ergebnis.testCaseWithSuffix.enabled: true`

## 0.6.0

### Upgrade notes

- **`rector/type-perfect` was replaced by `tomasvotruba/type-coverage` ^2.5** ([#30](https://github.com/konradmichalik/phpstan-typo3-preset/issues/30)). Remove `rector/type-perfect` from your own `require-dev`: type-coverage bundles the same rules, and PHPStan reports duplicate services when both are present. `phpstan/phpstan` 2.2 or later is now required. type-coverage 2.5 runs on PHP 7.4 or later, so the PHP constraint is unchanged. It also pulls in `entropy/entropy`, which is licensed as proprietary

## 0.5.0

### Upgrade notes

- **ergebnis/phpstan-rules runs in opt-in mode** ([#17](https://github.com/konradmichalik/phpstan-typo3-preset/issues/17)). `noNamedArgument`, `noConstructorParameterWithDefaultValue`, `noParameterPassedByReference` and `noPhpstanIgnore` are no longer active. Remove the matching `ignoreErrors` entries and `ergebnis` overrides, then regenerate the baseline with `--generate-baseline`. Inline `@phpstan-ignore <identifier>` comments work again
- **`type_coverage.constant` defaults to `0`** ([#18](https://github.com/konradmichalik/phpstan-typo3-preset/issues/18)). Remove the `constant: 0` override. `type_coverage.declare` is no longer set, because `ergebnis.declareStrictTypes` already enforces `declare(strict_types=1)`
- **The backend request attribute `route` is mapped** ([#19](https://github.com/konradmichalik/phpstan-typo3-preset/issues/19)). Remove your own `route` mapping. Keep a custom `routing` mapping for backend code, the default still maps the frontend types
- **PHPStan 1 is no longer supported** ([#20](https://github.com/konradmichalik/phpstan-typo3-preset/issues/20)). The configuration did not work with it since 0.1.4. The minimum versions are now `phpstan/phpstan` 2.1, `ergebnis/phpstan-rules` 2.13 and `tomasvotruba/type-coverage` 2.0.1
- **New disallowed calls** ([#23](https://github.com/konradmichalik/phpstan-typo3-preset/issues/23)): `dump()`, `dd()`, `VarDumper::dump()`, `$_REQUEST` and `$_COOKIE` may report new errors

### Changes

- `tomasvotruba/cognitive-complexity` is no longer capped below 1.2.0 ([#21](https://github.com/konradmichalik/phpstan-typo3-preset/issues/21))
- `rector/type-perfect` is abandoned. The migration to `tomasvotruba/type-coverage` 2.3 follows once PHP 8.4 is the minimum requirement ([#30](https://github.com/konradmichalik/phpstan-typo3-preset/issues/30))

## 0.4.0

### Upgrade notes

- **ergebnis/phpstan-rules was added** ([#12](https://github.com/konradmichalik/phpstan-typo3-preset/pull/12)). The preset only disabled single rules, so every other ergebnis rule was active. This made the release breaking for most extensions: `noNamedArgument`, `noConstructorParameterWithDefaultValue`, `noParameterPassedByReference`, `noErrorSuppression` and `finalInAbstractClass` report errors in typical TYPO3 code. With ergebnis 2.13 or later, `noPhpstanIgnore` also reports inline `@phpstan-ignore` comments and cannot be ignored or baselined. 0.5.0 disables `noNamedArgument`, `noConstructorParameterWithDefaultValue`, `noParameterPassedByReference` and `noPhpstanIgnore`, so upgrade instead of adding ignores for these. `noErrorSuppression` and `finalInAbstractClass` stay active
- **`tomasvotruba/type-coverage` is capped below 2.3.0** ([#10](https://github.com/konradmichalik/phpstan-typo3-preset/pull/10)), because 2.3 bundles `rector/type-perfect` and registers its services a second time
