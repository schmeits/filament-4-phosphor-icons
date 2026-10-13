<?php

declare(strict_types=1);

/**
 * Smoke tests for the public enums this package exposes.
 *
 * WHY: the Phosphor and PhosphorWeight enums are the entire public API.
 * Consumers type-hint against them in their resources and widgets. If their
 * shape changes, it is the consumer's code that breaks, not ours.
 */

use Filament\Support\Contracts\ScalableIcon;
use Filament\Support\Enums\IconSize;
use Schmeits\FilamentPhosphorIcons\Support\Icons\Phosphor;
use Schmeits\FilamentPhosphorIcons\Support\Icons\PhosphorWeight;

it('exposes Phosphor as a string-backed enum', function () {
    expect(Phosphor::class)->toBeString()
        ->and(enum_exists(Phosphor::class))->toBeTrue()
        ->and((new ReflectionEnum(Phosphor::class))->getBackingType()?->getName())->toBe('string');
});

it('implements the ScalableIcon contract from Filament', function () {
    // Filament calls getIconForSize() as soon as an icon is used in a component.
    // If this contract ever disappears from Filament, this test must turn red.
    expect(Phosphor::Acorn)->toBeInstanceOf(ScalableIcon::class);
});

it('exposes PhosphorWeight with exactly the six Phosphor weights', function () {
    // The icon source ships six variants per icon. If one is added or removed,
    // our mapping to file names no longer matches.
    expect(array_column(PhosphorWeight::cases(), 'value'))
        ->toEqualCanonicalizing(['thin', 'light', 'regular', 'bold', 'fill', 'duotone']);
});

it('adds no suffix for the regular weight', function () {
    // Regular has no suffix in blade-phosphor-icons (acorn.svg, not
    // acorn-regular.svg). This is the only exception in the mapping.
    expect(Phosphor::Acorn->getIconForWeight(PhosphorWeight::Regular))
        ->toBe('phosphor-acorn');
});

it('adds a suffix for every other weight', function () {
    expect(Phosphor::Acorn->getIconForWeight(PhosphorWeight::Duotone))
        ->toBe('phosphor-acorn-duotone');
});

it('covers every Filament IconSize in getIconForSize', function () {
    // match() without a default throws an UnhandledMatchError the moment
    // Filament adds a new IconSize. This test catches that for the consumer.
    foreach (IconSize::cases() as $size) {
        expect(Phosphor::Acorn->getIconForSize($size))->toStartWith('phosphor-acorn');
    }
});

it('uses a unique value for every icon name', function () {
    // A duplicate value would mean two enum cases point at the same icon,
    // usually a copy-paste mistake when adding new icons.
    $values = array_column(Phosphor::cases(), 'value');

    expect(array_diff_assoc($values, array_unique($values)))->toBeEmpty();
});

it('uses kebab-case icon names exclusively', function () {
    // blade-phosphor-icons only ships kebab-case file names. A case with an
    // uppercase letter or an underscore never resolves to an SVG.
    $invalid = array_filter(
        array_column(Phosphor::cases(), 'value'),
        fn (string $value): bool => preg_match('/^[a-z0-9]+(-[a-z0-9]+)*$/', $value) !== 1,
    );

    expect($invalid)->toBeEmpty();
});
