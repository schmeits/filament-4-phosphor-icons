<?php

declare(strict_types=1);

/*
 * tests/FilamentRenderTest.php
 *
 * Renders the enum through Filament's own icon component.
 *
 * WHY: the file checks prove an SVG exists, but consumers never call
 * blade-icons directly. They pass the enum to Filament, which calls
 * getIconForSize(). These tests prove that path works on the installed
 * Filament version (4 or 5) and picks the right weight per size.
 */

use Filament\Support\Enums\IconSize;
use Illuminate\Support\Facades\Blade;
use Schmeits\FilamentPhosphorIcons\Support\Icons\Phosphor;

/**
 * The first path of an icon file, used to recognise which weight was rendered.
 */
function phosphorFirstPath(string $iconName): string
{
    $svg = (string) file_get_contents(phosphorSvgPath() . '/' . $iconName . '.svg');

    if (preg_match('/\sd="([^"]+)"/', $svg, $matches) !== 1) {
        throw new RuntimeException("No path found in {$iconName}.svg");
    }

    return $matches[1];
}

it('renders the enum through the Filament icon component', function () {
    $html = Blade::render('<x-filament::icon :icon="$icon" />', ['icon' => Phosphor::Acorn]);

    expect($html)->toContain('<svg');
});

it('renders the weight that belongs to each Filament icon size', function (IconSize $size, string $file) {
    $html = Blade::render(
        '<x-filament::icon :icon="$icon" :size="$size" />',
        ['icon' => Phosphor::Acorn, 'size' => $size],
    );

    expect($html)->toContain(phosphorFirstPath($file));
})->with([
    'small renders thin' => [IconSize::Small, 'acorn-thin'],
    'medium renders regular' => [IconSize::Medium, 'acorn'],
    'large renders bold' => [IconSize::Large, 'acorn-bold'],
]);
