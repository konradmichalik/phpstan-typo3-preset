# AGENTS.md

Guidance for coding agents working in this repository.

## Project overview

`konradmichalik/phpstan-typo3-preset` is a Composer library that ships a TYPO3 preset configuration for PHPStan. It contains configuration only, there is no PHP source code. It is intended for the maintainer's own projects and is not designed for general use.

- PHP: `~8.2.0 || ~8.3.0 || ~8.4.0 || ~8.5.0`
- PHPStan: `^1.9 || ^2.0`
- License: GPL-3.0-or-later
- Bundled tooling: `saschaegerer/phpstan-typo3`, `phpstan/phpstan-strict-rules`, `phpstan/phpstan-deprecation-rules`, `spaze/phpstan-disallowed-calls`, `tomasvotruba/type-coverage`, `tomasvotruba/cognitive-complexity`, `rector/type-perfect`, `ergebnis/phpstan-rules`

## Structure

```
extension.neon       Base preset, auto-included via phpstan/extension-installer
phpstan.neon.dist    Manual entry point that includes the tool configs and extension.neon
composer.json        Package metadata, extra.phpstan.includes, lint scripts
.github/workflows/   Release workflow (reusable workflow from konradmichalik/reusable-github-actions)
```

- `extension.neon` holds the rules: type coverage thresholds, cognitive complexity limits, strict rules, type-perfect, ergebnis rules and the disallowed calls (`var_dump()`, `header()`, `$_GET`, `$_POST`, `$_FILES`, `$_SERVER` and similar).
- `phpstan.neon.dist` includes the third-party extension files and `extension.neon`. Keep both in sync when adding a dependency.

## Development commands

```bash
composer lint              # composer normalize (dry run) and editorconfig check
composer lint:composer     # composer.json normalization check
composer lint:editorconfig # ec --git-only
composer fix               # apply composer normalize and editorconfig fixes
```

## Testing

No test suite is configured. Verify a preset change by running PHPStan in a consuming project with the changed `extension.neon` or `phpstan.neon.dist` included.

## Code style and static analysis

- Follow `.editorconfig`: UTF-8, LF, final newline, 4 spaces by default. JSON and NEON files use tabs, YAML uses 2 spaces.
- `composer.json` is normalized with `ergebnis/composer-normalize`, run `composer fix` after editing it.
- Tomasvotruba packages are version-capped on purpose (`type-coverage` below 2.3.0, `cognitive-complexity` below 1.2.0) to avoid duplicate type-perfect registration. Do not widen these ranges without checking.
- No PHP-CS-Fixer, PHPStan self-analysis or Rector configuration exists here.

## CI

The only workflow is `release.yml`, which runs on tag push and delegates to a reusable release workflow. There is no CI job for lint or tests.

## Git workflow

- Commit format: `<type>: <description>`
- Types: `feat`, `fix`, `refactor`, `docs`, `test`, `chore`, `perf`, `ci`
- No co-author trailers
- One commit per logical change
