<?php

namespace Schmeits\FilamentPhosphorIcons;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentPhosphorIconsServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-4-phosphor-icons';

    public static string $viewNamespace = 'filament-4-phosphor-icons';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name);
    }

    public function packageRegistered(): void {}

    public function packageBooted(): void {}

    protected function getAssetPackageName(): ?string
    {
        return 'schmeits/filament-4-phosphor-icons';
    }
}
