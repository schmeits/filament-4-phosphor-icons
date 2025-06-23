<?php
// This script reads all SVGs from the local vendor directory and generates the enum file.

$svgDir = __DIR__ . '/vendor/codeat3/blade-phosphor-icons/resources/svg';
$enumFile = __DIR__ . '/src/Support/Icons/Phosphor.php';

if (! is_dir($svgDir)) {
    exit("SVG directory not found: $svgDir\n");
}

$enumCases = [];
$files = scandir($svgDir);

foreach ($files as $file) {
    if (preg_match('/^(.*)\.svg$/', $file, $matches)) {
        $iconName = $matches[1];

        $needles = ['-fill', '-light', '-thin', '-duotone', '-regular', '-bold'];
        $skip = false;
        foreach ($needles as $needle) {
            if ($needle !== '' && str_ends_with($iconName, $needle)) {
                $skip = true;
            }
        }

        if ($skip) {
            continue;
        }

        $enumKey = str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $iconName)));

        // If the key starts with a number, prefix with an underscore
        if (preg_match('/^\d/', $enumKey)) {
            $enumKey = '_' . $enumKey;
        }

        $enumCases[] = "    case $enumKey = '$iconName';";
    }
}

$enumContent = '<?php

namespace Schmeits\\FilamentPhosphorIcons\\Support\\Icons;

use Filament\\Support\\Contracts\\ScalableIcon;
use Filament\\Support\\Enums\\IconSize;

enum Phosphor: string implements ScalableIcon
{
' . implode("\n", $enumCases) . '

    public function getIconForSize(IconSize $size): string
    {
        return match ($size) {
            IconSize::ExtraSmall, IconSize::Small => "phosphor-{$this->value}-thin",
            IconSize::Medium => "phosphor-{$this->value}",
            IconSize::Large, IconSize::ExtraLarge, IconSize::TwoExtraLarge => "phosphor-{$this->value}-bold",
        };
    }

    public function getIconForWeight(PhosphorWeight $weight): string
    {
        if ($weight === PhosphorWeight::Regular) {
            return "phosphor-{$this->value}";
        }

        return "phosphor-{$this->value}-{$weight->value}";
    }
}
';

file_put_contents($enumFile, $enumContent);

echo 'Phosphor Icon enum generated with ' . count($enumCases) . " icons from local vendor directory.\n";
