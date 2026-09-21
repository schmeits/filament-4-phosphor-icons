<?php

/**
 * Smoke tests for the package's service provider.
 *
 * WHY: this package is loaded via package discovery into every Filament app.
 * If the provider stops registering or booting, every app that uses it falls
 * over on startup. This is the first domino we guard.
 */

use BladeUI\Icons\Factory;
use Illuminate\Support\ServiceProvider;
use Schmeits\FilamentPhosphorIcons\FilamentPhosphorIconsServiceProvider;

it('registers the service provider in the application', function () {
    // getLoadedProviders() only contains providers that registered and booted
    // without errors, so this proves both phases at once.
    expect(app()->getLoadedProviders())
        ->toHaveKey(FilamentPhosphorIconsServiceProvider::class);
});

it('is a Laravel service provider', function () {
    expect(new FilamentPhosphorIconsServiceProvider(app()))
        ->toBeInstanceOf(ServiceProvider::class);
});

it('keeps the package name stable', function () {
    // The name drives how spatie/laravel-package-tools names config, views and
    // assets. If the name changes, published paths break for consumers.
    expect(FilamentPhosphorIconsServiceProvider::$name)
        ->toBe('filament-4-phosphor-icons');
});

it('loads the underlying blade-phosphor-icons icon set', function () {
    // The package relies entirely on codeat3's icon set. If that registration
    // disappears after an upgrade, no icon resolves anymore.
    expect(app(Factory::class)->all())->toHaveKey('phosphor-icons');
});

it('runs the test suite on an in-memory sqlite database', function () {
    // Explicit safeguard: these tests must never touch a real database.
    expect(config('database.default'))->toBe('testing')
        ->and(config('database.connections.testing.driver'))->toBe('sqlite')
        ->and(config('database.connections.testing.database'))->toBe(':memory:');
});
