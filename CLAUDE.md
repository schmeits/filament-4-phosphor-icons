# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What is this?

A Laravel package that provides Phosphor icons as a custom icon pack for Filament v4. It wraps `codeat3/blade-phosphor-icons` and exposes a `Phosphor` enum that implements Filament's `ScalableIcon` interface, so icons automatically scale with Filament's `IconSize` system.

Published on Packagist as `schmeits/filament-4-phosphor-icons`.

## Commands

All commands require the Herd PATH prefix on this machine:

```bash
export PATH="C:/Users/Schmeitswebsolutions/.config/herd/bin/php84:C:/Users/Schmeitswebsolutions/.config/herd/bin:$PATH"
```

| Task | Command |
|------|---------|
| Run tests | `php vendor/bin/pest` |
| Run single test | `php vendor/bin/pest --filter="test name"` |
| Static analysis | `php vendor/bin/phpstan analyse` |
| Code style fix | `php vendor/bin/pint` |
| Code style (dirty only) | `php vendor/bin/pint --dirty` |

PHPStan runs at **level 4** with Octane compatibility and model property checks enabled (see `phpstan.neon.dist`).

## Architecture

This is a minimal package — three source files total:

- **`src/FilamentPhosphorIconsServiceProvider.php`** — Registers the package via `spatie/laravel-package-tools`. No config, no views, no migrations.
- **`src/Support/Icons/Phosphor.php`** — Backed string enum with ~1500 icon cases. Implements `Filament\Support\Contracts\ScalableIcon`. Two key methods:
  - `getIconForSize(IconSize)` — maps Filament icon sizes to weight variants (thin for small, regular for medium, bold for large)
  - `getIconForWeight(PhosphorWeight)` — returns the Blade icon name with explicit weight suffix (e.g. `phosphor-smiley-duotone`)
- **`src/Support/Icons/PhosphorWeight.php`** — Enum with six weights: Thin, Light, Regular, Bold, Fill, Duotone.

Icon names follow the pattern `phosphor-{icon-name}` (regular) or `phosphor-{icon-name}-{weight}` (other weights). These map to the underlying `codeat3/blade-phosphor-icons` Blade components.

## Testing

Tests use **Pest v3** with **Orchestra Testbench**. The `TestCase` registers all Filament service providers. Currently has architecture tests (no debug functions) and a placeholder test.

## Dependencies

- **Runtime**: `filament/filament ^v4.0.0-beta`, `codeat3/blade-phosphor-icons ^2.3`, `spatie/laravel-package-tools ^1.15.0`
- **PHP**: `^8.2`
- **Stability**: `minimum-stability: beta` (required for Filament v4 beta)

## Adding new icons

When Phosphor releases new icons, add cases to the `Phosphor` enum in `src/Support/Icons/Phosphor.php`. The case name is PascalCase, the value is the kebab-case icon name (matching the blade-phosphor-icons naming).

## CI/CD

GitHub Actions workflows:
- `fix-php-code-style-issues.yml` — Auto-fixes code style with Pint
- `phpstan.yml` — Runs static analysis
- `update-changelog.yml` — Changelog automation
- `dependabot-auto-merge.yml` — Auto-merges Dependabot PRs
