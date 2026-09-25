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
```

## 💛 Acknowledgements

This project is partly based on the best practices of [tea](https://github.com/TYPO3BestPractices/tea) extension.

## ⭐ License

This project is licensed under [GNU General Public License 3.0 (or later)](LICENSE).
