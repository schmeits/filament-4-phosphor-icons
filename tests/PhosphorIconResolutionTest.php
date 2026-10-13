<?php

declare(strict_types=1);

/**
 * Smoke tests that prove every enum case actually points at an existing SVG
 * in the icon source codeat3/blade-phosphor-icons.
 *
 * WHY: this is exactly what breaks when the icon source is upgraded. Phosphor
 * occasionally renames or removes icons. The enum still compiles, but in
 * production the consumer hits an SvgNotFound exception the moment the page
 * renders. These tests surface that early.
 */

use BladeUI\Icons\Exceptions\SvgNotFound;
use BladeUI\Icons\Factory;
use Filament\Support\Enums\IconSize;
use Schmeits\FilamentPhosphorIcons\Support\Icons\Phosphor;
use Schmeits\FilamentPhosphorIcons\Support\Icons\PhosphorWeight;

/**
 * Translates a blade-icon name (phosphor-acorn-bold) to the file on disk.
 */
function phosphorFile(string $iconName): string
{
    return phosphorSvgPath() . '/' . str($iconName)->after('phosphor-')->toString() . '.svg';
}

it('finds an SVG file for every icon at every weight', function (PhosphorWeight $weight) {
    // A dataset per weight instead of everything in one loop: if something
    // fails you immediately see which weight is missing from the new source.
    $missing = [];

    foreach (Phosphor::cases() as $icon) {
        $file = phosphorFile($icon->getIconForWeight($weight));

        if (! file_exists($file)) {
            $missing[] = $icon->value;
        }
    }

    expect($missing)->toBeEmpty(
        'Missing SVG files for weight ' . $weight->value . ': ' . implode(', ', array_slice($missing, 0, 20)),
    );
})->with(fn (): array => PhosphorWeight::cases());

it('finds an existing SVG file for every Filament IconSize', function (IconSize $size) {
    // getIconForSize() maps to thin, regular or bold. If one of those weights
    // disappears from the source, icons no longer scale but crash instead.
    $missing = [];

    foreach (Phosphor::cases() as $icon) {
        $file = phosphorFile($icon->getIconForSize($size));

        if (! file_exists($file)) {
            $missing[] = $icon->value;
        }
    }

    expect($missing)->toBeEmpty(
        'Missing SVG files for size ' . $size->value . ': ' . implode(', ', array_slice($missing, 0, 20)),
    );
})->with(fn (): array => IconSize::cases());

it('covers every icon from the source with the enum', function () {
    // The reverse check: if the source ships new icons, the enum is missing
    // them. That is not a crash but a gap we deliberately want to see after a
    // dependency upgrade, so we can update the enum.
    $files = phosphorSvgFiles();

    $suffixes = array_map(
        fn (PhosphorWeight $weight): string => '-' . $weight->value,
        array_filter(PhosphorWeight::cases(), fn (PhosphorWeight $weight): bool => $weight !== PhosphorWeight::Regular),
    );

    $fromSource = [];

    foreach ($files as $file) {
        $name = basename($file, '.svg');

        // Only the regular files count as a unique icon; the rest are weight
        // variants of that same icon.
        foreach ($suffixes as $suffix) {
            if (str_ends_with($name, $suffix)) {
                continue 2;
            }
        }

        $fromSource[] = $name;
    }

    $fromEnum = array_column(Phosphor::cases(), 'value');

    expect(array_values(array_diff($fromSource, $fromEnum)))->toBeEmpty(
        'Icons in the source that are not yet in the enum.',
    );
});

it('has as many enum cases as unique icons in the source', function () {
    // A hard count alongside the diff above: if the number changes, something
    // about the source has changed and someone needs to look at it.
    $fileCount = count(phosphorSvgFiles());

    expect(count(Phosphor::cases()) * count(PhosphorWeight::cases()))
        ->toBe($fileCount);
});

it('actually renders an icon as SVG markup', function () {
    // Checking the file alone is not enough: this proves blade-icons can
    // actually fetch and render the icon inside a Laravel app.
    $svg = app(Factory::class)->svg('phosphor-acorn')->toHtml();

    expect($svg)->toContain('<svg')->toContain('</svg>');
});

it('also renders a weight variant as SVG markup', function () {
    $svg = app(Factory::class)->svg(Phosphor::Acorn->getIconForWeight(PhosphorWeight::Duotone))->toHtml();

    expect($svg)->toContain('<svg');
});

it('throws a clear error for an unknown icon', function () {
    // A negative check on the source itself: this proves a missing icon really
    // raises an exception instead of silently rendering nothing.
    app(Factory::class)->svg('phosphor-this-icon-does-not-exist');
})->throws(SvgNotFound::class);
