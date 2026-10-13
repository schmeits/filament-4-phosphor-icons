<?php

declare(strict_types=1);

/*
 * tests/Pest.php
 *
 * Binds every test in this directory to the package TestCase and holds the
 * helpers that more than one test file needs.
 */

use BladeUI\Icons\Factory;
use Schmeits\FilamentPhosphorIcons\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

/**
 * Path to the icon source's SVG directory, resolved through the registered set.
 * Deliberately not hardcoded: this way the tests also prove the registration is
 * correct.
 */
function phosphorSvgPath(): string
{
    $set = app(Factory::class)->all()['phosphor-icons'] ?? null;
    $path = is_array($set) && is_array($set['paths'] ?? null) ? ($set['paths'][0] ?? null) : null;

    if (! is_string($path)) {
        throw new RuntimeException('The phosphor-icons set is not registered with blade-icons.');
    }

    return $path;
}

/**
 * All SVG files in the icon source.
 *
 * @return list<string>
 */
function phosphorSvgFiles(): array
{
    return glob(phosphorSvgPath() . '/*.svg') ?: [];
}
