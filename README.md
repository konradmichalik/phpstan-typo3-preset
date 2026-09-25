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
  level: 7

  paths:
    - Classes
    - Configuration
    - Resources
    - Tests/Unit

  excludePaths:
    - .Build (?)

  type_coverage:
    constant: 0 # TODO: Remove when PHP 8.3 is minimum requirement
```

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

## 💛 Acknowledgements

This project is partly based on the best practices of [tea](https://github.com/TYPO3BestPractices/tea) extension.

## ⭐ License

This project is licensed under [GNU General Public License 3.0 (or later)](LICENSE).
