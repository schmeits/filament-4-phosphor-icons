# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What is this?

A Laravel package that provides Phosphor icons as a custom icon pack for Filament 4 and 5. It wraps `codeat3/blade-phosphor-icons` and exposes a `Phosphor` enum that implements Filament's `ScalableIcon` interface, so icons automatically scale with Filament's `IconSize` system.

Published on Packagist as `schmeits/filament-4-phosphor-icons`.

## Commands

| Task | Command |
|------|---------|
| Run tests | `composer test` |
| Run single test | `vendor/bin/pest --filter="test name"` |
| Static analysis | `composer analyse` |
| Code style fix | `composer format` |
| Regenerate the enum | `composer generate-enum` |

PHPStan runs at **level max** on `src`, `tests` and `bin` (see `phpstan.neon.dist`).

## Architecture

- **`src/FilamentPhosphorIconsServiceProvider.php`**: registers the package via `spatie/laravel-package-tools`. No config, no views, no migrations.
- **`src/Support/Icons/Phosphor.php`**: backed string enum with ~1500 icon cases. Generated, never edit by hand. Implements `Filament\Support\Contracts\ScalableIcon`:
  - `getIconForSize(IconSize)` maps Filament sizes to weights (thin for XS/S, regular for M, bold for L and up). Changing this mapping is a breaking change for users.
  - `getIconForWeight(PhosphorWeight)` returns the Blade icon name with an explicit weight suffix (e.g. `phosphor-smiley-duotone`).
- **`src/Support/Icons/PhosphorWeight.php`**: the six weights Thin, Light, Regular, Bold, Fill, Duotone.
- **`bin/generate-enum.php`**: generates the enum from the installed upstream SVGs and writes the upstream version into the file header.

Icon names follow the pattern `phosphor-{icon-name}` (regular) or `phosphor-{icon-name}-{weight}` (other weights).

## Testing

Pest with Orchestra Testbench. The `TestCase` registers the Filament providers explicitly, because Testbench does not run package discovery. The suite covers:

- every enum case resolves to an SVG at every weight and size, and the reverse: every upstream icon has an enum case;
- rendering through `<x-filament::icon>` picks the right weight per size;
- the committed enum equals the generator output;
- architecture rules (strict types, no debug calls).

CI runs the tests on PHP 8.2 to 8.5 x Laravel 11 to 13 x Filament 4 and 5.

## Adding new icons

Upgrade `codeat3/blade-phosphor-icons`, run `composer generate-enum`, run the tests, and raise the minimum version of `codeat3/blade-phosphor-icons` in `composer.json` to the version you generated from.

## Code conventions

- `declare(strict_types=1)` and a header comment (path and purpose) in every PHP file.
- No em-dashes in code, comments or docs.
