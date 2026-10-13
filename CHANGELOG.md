# Changelog

All notable changes to `filament-4-phosphor-icons` will be documented in this file.

## Unreleased

- Support Filament 5 next to Filament 4, and Laravel 13. Laravel 11 is end-of-life and no longer tested.
- Require Filament 4.13.2 or 5.8.2 or higher, the first versions without known security advisories.
- Require codeat3/blade-phosphor-icons 2.4 or higher, the version the enum is generated from.
- The enum generator moved to `bin/generate-enum.php` (`composer generate-enum`) and records the upstream version in the generated file.
- Tests now run in CI on PHP 8.2 to 8.5, Laravel 12 and 13 and Filament 4 and 5, including a render test through Filament's icon component and a check that the enum matches the generator.
- PHPStan on level max for the source, the tests and the generator.
- Every PHP file declares strict types.
- Removed unused template code: the `$viewNamespace` property on the service provider, the non-existent facade alias and factory namespace, and the unused frontend tooling.

## 1.0.6 - 2025-06-18

- Allow the Filament 4 beta.

## 1.0.5 - 2025-06-18

- Changelog update.

## 1.0.4 - 2025-05-13

- Fix: regular weight has no suffix.

## 1.0.3 - 2025-05-13

- Fix the suffix for weights.

## 1.0.2 - 2025-05-13

- README with weight usage.

## 1.0.1 - 2025-05-13

- Support weights.

## 1.0.0 - 2025-05-12

- First release of the Phosphor icons for Filament 4.
