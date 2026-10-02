# AGENTS.md

Guidance for coding agents working in this repository.

## Project overview

`konradmichalik/phpstan-typo3-preset` is a Composer library that ships a TYPO3 preset configuration for PHPStan. It contains configuration only, there is no PHP source code. It is intended for the maintainer's own projects and is not designed for general use.

- PHP: `~8.2.0 || ~8.3.0 || ~8.4.0 || ~8.5.0`
- PHPStan: `^2.1`
- License: GPL-3.0-or-later
- Bundled tooling: `saschaegerer/phpstan-typo3`, `phpstan/phpstan-strict-rules`, `phpstan/phpstan-deprecation-rules`, `spaze/phpstan-disallowed-calls`, `tomasvotruba/type-coverage`, `tomasvotruba/cognitive-complexity`, `rector/type-perfect`, `ergebnis/phpstan-rules`

## Structure

```
extension.neon       Base preset, auto-included via phpstan/extension-installer
phpstan.neon.dist    Manual entry point that includes the tool configs and extension.neon
composer.json        Package metadata, extra.phpstan.includes, lint and test scripts
CHANGELOG.md         Release notes with upgrade notes per version
tests/               Smoke test: fixtures analysed with the preset, compared against expected-errors.json
.github/workflows/   Tests workflow and release workflow (reusable workflow from konradmichalik/reusable-github-actions)
```

- `extension.neon` holds the rules: type coverage thresholds, cognitive complexity limits, strict rules, type-perfect, ergebnis rules in opt-in mode (`allRules: false`) and the disallowed calls (`var_dump()`, `dump()`, `dd()`, `header()`, `$_GET`, `$_POST`, `$_REQUEST`, `$_COOKIE` and similar).
- `phpstan.neon.dist` includes the third-party extension files and `extension.neon`. Keep both in sync when adding a dependency.

## Development commands

```bash
composer lint              # composer normalize (dry run) and editorconfig check
composer lint:composer     # composer.json normalization check
composer lint:editorconfig # ec --git-only
composer fix               # apply composer normalize and editorconfig fixes
composer test              # run the smoke test
composer test:update       # rewrite tests/expected-errors.json from the current result
```

## Testing

`composer test` analyses the fixtures in `tests/Fixture` with `tests/phpstan.neon` and fails when the reported errors differ from `tests/expected-errors.json`. After an intended rule change, run `composer test:update` and review the diff of `expected-errors.json`.

## Code style and static analysis

- Follow `.editorconfig`: UTF-8, LF, final newline, 4 spaces by default. JSON and NEON files use tabs, YAML uses 2 spaces.
- `composer.json` is normalized with `ergebnis/composer-normalize`, run `composer fix` after editing it.
- `tomasvotruba/type-coverage` is capped below 2.3.0 on purpose, because 2.3 bundles type-perfect and registers its services a second time. Do not widen the range before the migration in #30.
- No PHP-CS-Fixer, PHPStan self-analysis or Rector configuration exists here.

## CI

- `tests.yml` runs `composer validate --strict`, `composer lint` and `composer test` on PHP 8.2 to 8.5 with lowest and highest dependencies.
- `release.yml` runs on tag push and delegates to a reusable release workflow. GitHub generates the release notes from the merged pull requests, so keep `CHANGELOG.md` up to date by hand.

## Git workflow

- Commit format: `<type>: <description>`
- Types: `feat`, `fix`, `refactor`, `docs`, `test`, `chore`, `perf`, `ci`
- No co-author trailers
- One commit per logical change
