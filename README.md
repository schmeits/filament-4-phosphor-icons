# Phosphor icons for Filament 4 and 5

[![Latest Version on Packagist](https://img.shields.io/packagist/v/schmeits/filament-4-phosphor-icons.svg?style=flat-square)](https://packagist.org/packages/schmeits/filament-4-phosphor-icons)
[![Tests](https://img.shields.io/github/actions/workflow/status/schmeits/filament-4-phosphor-icons/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/schmeits/filament-4-phosphor-icons/actions/workflows/run-tests.yml)
[![PHPStan](https://img.shields.io/github/actions/workflow/status/schmeits/filament-4-phosphor-icons/phpstan.yml?branch=main&label=phpstan&style=flat-square)](https://github.com/schmeits/filament-4-phosphor-icons/actions/workflows/phpstan.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/schmeits/filament-4-phosphor-icons.svg?style=flat-square)](https://packagist.org/packages/schmeits/filament-4-phosphor-icons)

All [Phosphor icons](https://phosphoricons.com/) as a backed enum for Filament. The enum implements Filament's `ScalableIcon` contract, so every icon follows Filament's icon sizes, and you can pick any of the six Phosphor weights yourself.

The icons themselves come from [codeat3/blade-phosphor-icons](https://github.com/codeat3/blade-phosphor-icons).

## Compatibility

| Package | Filament | Laravel | PHP |
|---------|----------|---------|-----|
| 1.1     | 4.13.2+ and 5.8.2+ | 12, 13 | 8.2+ (Laravel 13: 8.3+) |
| 1.0     | 4.x | 11, 12 | 8.2+ |

The minimum Filament versions are the first ones without known security advisories.

## Installation

```bash
composer require schmeits/filament-4-phosphor-icons
```

## Usage

```php
use Filament\Forms\Components\TextInput;
use Schmeits\FilamentPhosphorIcons\Support\Icons\Phosphor;

TextInput::make('name')
    ->required()
    ->prefixIcon(Phosphor::Smiley);
```

Find the icon you need on [phosphoricons.com](https://phosphoricons.com/). The enum case is the icon name in PascalCase: `smiley-sticker` becomes `Phosphor::SmileySticker`.

### Sizes

When you pass the enum itself, Filament asks it for an icon per size. This package maps the sizes to a weight:

| Filament size | Weight |
|---------------|--------|
| `ExtraSmall`, `Small` | thin |
| `Medium` | regular |
| `Large`, `ExtraLarge`, `TwoExtraLarge` | bold |

### Weights

To use one weight everywhere, pick it explicitly:

```php
use Schmeits\FilamentPhosphorIcons\Support\Icons\Phosphor;
use Schmeits\FilamentPhosphorIcons\Support\Icons\PhosphorWeight;

->prefixIcon(Phosphor::Smiley->getIconForWeight(PhosphorWeight::Duotone))
```

The available weights are `Thin`, `Light`, `Regular`, `Bold`, `Fill` and `Duotone`.

## Updating the icons

The enum is generated from the installed version of `codeat3/blade-phosphor-icons`. After upgrading that package:

```bash
composer generate-enum
```

The test suite fails when the enum and the icon source no longer match, in either direction.

## Testing

```bash
composer test
composer analyse
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Security

Please see [our security policy](.github/SECURITY.md) on how to report security vulnerabilities.

## Credits

- [Tally Schmeits](https://github.com/schmeits)
- [Phosphor icons](https://phosphoricons.com)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
