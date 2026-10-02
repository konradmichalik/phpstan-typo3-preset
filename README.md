<div align="center">

![icon](icon.png)

# TYPO3 PHPStan Preset

</div>

This package provides a basic TYPO3 [PHPStan](https://phpstan.org/) configuration.

> [!IMPORTANT]
> This package is intended for use in my personal projects only. It is not designed for general use.

## 🔥 Installation

[![Packagist](https://img.shields.io/packagist/v/konradmichalik/phpstan-typo3-preset?label=version&logo=packagist)](https://packagist.org/packages/konradmichalik/phpstan-typo3-preset)
[![Packagist Downloads](https://img.shields.io/packagist/dt/konradmichalik/phpstan-typo3-preset?color=brightgreen)](https://packagist.org/packages/konradmichalik/phpstan-typo3-preset)

```bash
composer require konradmichalik/phpstan-typo3-preset --dev
```

## ⚡ Usage

If you have the [`phpstan/extension-installer`](https://github.com/phpstan/extension-installer)
package installed, there's nothing more to do. The [base configuration](extension.neon)
is automatically included.

### Manual Setup

```yaml
# phpstan.neon

includes:
  - %rootDir%/../../konradmichalik/phpstan-typo3-preset/phpstan.neon.dist

parameters:
  level: 8

  paths:
    - Classes
    - Configuration
    - Resources
    - Tests/Unit

  excludePaths:
    - .Build (?)
```

## 🧰 Included Rule Sets

| Package | Configuration |
|---------|---------------|
| [`saschaegerer/phpstan-typo3`](https://github.com/sascha-egerer/phpstan-typo3) | TYPO3 types. The request attribute mapping additionally covers the backend attribute `route` |
| [`phpstan/phpstan-strict-rules`](https://github.com/phpstan/phpstan-strict-rules) | All rules except `disallowedShortTernary` |
| [`phpstan/phpstan-deprecation-rules`](https://github.com/phpstan/phpstan-deprecation-rules) | All rules |
| [`spaze/phpstan-disallowed-calls`](https://github.com/spaze/phpstan-disallowed-calls) | The dangerous, execution, insecure and loose call lists, plus the preset rules below |
| [`tomasvotruba/type-coverage`](https://github.com/TomasVotruba/type-coverage) | `return: 100`, `param: 95`, `property: 95`, `constant: 0` (typed constants need PHP 8.3) |
| [`tomasvotruba/cognitive-complexity`](https://github.com/TomasVotruba/cognitive-complexity) | `class: 40`, `function: 10` |
| [`rector/type-perfect`](https://github.com/rectorphp/type-perfect) | `no_mixed_property`, `no_mixed_caller`, `null_over_false`, `narrow_param`, `narrow_return` |
| [`ergebnis/phpstan-rules`](https://github.com/ergebnis/phpstan-rules) | Opt-in, see [below](#ergebnisphpstan-rules) |

The preset disallows these calls in addition to the spaze lists:

- Debug calls: `var_dump()`, `dump()`, `dd()`, `xdebug_break()`, `debug()`, `DebuggerUtility::var_dump()`, `DebugUtility::debug()`, `VarDumper::dump()`
- `header()` and the superglobals `$_GET`, `$_POST`, `$_FILES`, `$_SERVER`, `$_REQUEST`, `$_COOKIE` (use the PSR-7 API instead)

### ergebnis/phpstan-rules

The preset runs [`ergebnis/phpstan-rules`](https://github.com/ergebnis/phpstan-rules) in opt-in mode (`allRules: false`), so new ergebnis releases do not enable rules silently.

Enabled rules: `declareStrictTypes`, `finalInAbstractClass`, `invokeParentHookMethod`, `noAssignByReference`, `noCompact`, `noErrorSuppression`, `noEval`, `noReturnByReference`, `noSwitch`, `privateInFinalClass`, `testCaseWithSuffix`.

Deliberately disabled rules:

| Rule | Reason |
|------|--------|
| `final`, `noExtends` | TYPO3 APIs are inheritance based |
| `noIsset` | Too restrictive for array access on TYPO3 configuration and TCA |
| `noNullableReturnTypeDeclaration`, `noParameterWithNullableTypeDeclaration`, `noParameterWithNullDefaultValue` | Nullable types are part of many TYPO3 and Symfony APIs |
| `noParameterWithContainerTypeDeclaration` | Not relevant for TYPO3 extensions |
| `noNamedArgument` | Attribute APIs (`#[AsEventListener]`, Symfony `#[Route]`) and TYPO3 DTOs rely on named arguments |
| `noConstructorParameterWithDefaultValue` | Conflicts with optional services in Symfony DI and with value objects |
| `noParameterPassedByReference` | Signatures are dictated by TYPO3 (DataHandler hooks, `itemsProcFunc`, `userFunc`) |
| `noPhpstanIgnore` | Reported as non-ignorable and applied inconsistently. Inline `@phpstan-ignore <identifier>` keeps the reason next to the code |

Enable any of them in your project configuration if needed:

```yaml
parameters:
  ergebnis:
    noIsset:
      enabled: true
```

### rector/type-perfect

[`rector/type-perfect`](https://github.com/rectorphp/type-perfect) is deprecated and no longer receives fixes for new PHPStan releases. Its rules moved into `tomasvotruba/type-coverage` 2.3, which requires PHP 8.4 and registers the type-perfect services itself. Installing both leads to duplicate service errors, so the preset caps `tomasvotruba/type-coverage` at `<2.3.0`.

Once PHP 8.4 is the minimum requirement, the preset will drop `rector/type-perfect`, require `tomasvotruba/type-coverage: ^2.3` and remove the `narrow_param` option, which has no effect there since 2.4.

## 🛠️ Typical Overrides

Ignore a rule for a single file per identifier and path, and keep the reason next to it:

```yaml
parameters:
  ignoreErrors:
    # Methods of this abstract class are overridden by the concrete widgets
    -
      identifier: ergebnis.finalInAbstractClass
      path: Classes/Widgets/AbstractWidget.php
```

Map custom request attributes. The entries merge with the defaults of `saschaegerer/phpstan-typo3`:

```yaml
parameters:
  typo3:
    requestGetAttributeMapping:
      myAttribute: Vendor\Extension\Domain\Model\MyAttribute
```

Extensions supporting several TYPO3 majors run into ignores that only match one version. Allow unmatched ignores in that case:

```yaml
parameters:
  reportUnmatchedIgnoredErrors: false
```

## 🔄 Upgrading

See the [changelog](https://github.com/konradmichalik/phpstan-typo3-preset/blob/main/CHANGELOG.md) for upgrade notes per version.

## 🧑‍💻 Development

`composer test` analyses the fixture extension in `tests/Fixture` and compares the reported errors with `tests/expected-errors.json`. After a deliberate rule change, update the snapshot with `composer test:update` and review the diff.

## 💛 Acknowledgements

This project is partly based on the best practices of [tea](https://github.com/TYPO3BestPractices/tea) extension.

## ⭐ License

This project is licensed under [GNU General Public License 3.0 (or later)](LICENSE).
