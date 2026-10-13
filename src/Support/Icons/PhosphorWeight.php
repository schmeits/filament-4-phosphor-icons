<?php

declare(strict_types=1);

/*
 * src/Support/Icons/PhosphorWeight.php
 *
 * The six weights Phosphor ships for every icon. Use it with
 * Phosphor::getIconForWeight() to pick a weight explicitly.
 */

namespace Schmeits\FilamentPhosphorIcons\Support\Icons;

enum PhosphorWeight: string
{
    case Thin = 'thin';
    case Light = 'light';
    case Regular = 'regular';
    case Bold = 'bold';
    case Fill = 'fill';
    case Duotone = 'duotone';
}
