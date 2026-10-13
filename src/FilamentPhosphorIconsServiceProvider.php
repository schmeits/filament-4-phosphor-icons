<?php

declare(strict_types=1);

/*
 * src/FilamentPhosphorIconsServiceProvider.php
 *
 * Registers the package with Laravel. The icons themselves come from
 * codeat3/blade-phosphor-icons; this package only adds the Phosphor enum, so
 * there is no config, view or migration to register.
 */

namespace Schmeits\FilamentPhosphorIcons;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentPhosphorIconsServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-4-phosphor-icons';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name);
    }
}
