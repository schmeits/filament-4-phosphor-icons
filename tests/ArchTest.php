<?php

declare(strict_types=1);

/*
 * tests/ArchTest.php
 *
 * Architecture rules for the package source.
 */

use Filament\Support\Contracts\ScalableIcon;
use Schmeits\FilamentPhosphorIcons\Support\Icons\Phosphor;

arch('it will not use debugging functions', function () {
    expect(['dd', 'dump', 'ray', 'var_dump'])->not->toBeUsed();
});

arch('every source file declares strict types', function () {
    expect('Schmeits\FilamentPhosphorIcons')->toUseStrictTypes();
});

arch('the Phosphor enum is a Filament ScalableIcon', function () {
    expect(Phosphor::class)->toBeEnum()->toImplement(ScalableIcon::class);
});
