<?php

declare(strict_types=1);

/*
 * tests/GeneratorTest.php
 *
 * Proves the committed enum is exactly what bin/generate-enum.php produces.
 *
 * WHY: the enum is generated, never edited by hand. If someone edits it
 * anyway, or the generator changes without regenerating, the next run would
 * silently undo that work. The line with the upstream version is left out of
 * the comparison: a new upstream release without new icons is not a change.
 */

/**
 * The file contents without line-ending noise and without the version line.
 */
function normalisedEnum(string $file): string
{
    $contents = str_replace("\r\n", "\n", (string) file_get_contents($file));

    return (string) preg_replace('/^ \* Generated from .*$/m', '', $contents);
}

it('matches the output of the generator', function () {
    $root = dirname(__DIR__);
    $output = tempnam(sys_get_temp_dir(), 'phosphor-enum-');

    exec(
        escapeshellarg(PHP_BINARY) . ' ' . escapeshellarg($root . '/bin/generate-enum.php') . ' ' . escapeshellarg('--output=' . $output),
        result_code: $exitCode,
    );

    expect($exitCode)->toBe(0)
        ->and(normalisedEnum($output))->toBe(normalisedEnum($root . '/src/Support/Icons/Phosphor.php'));

    unlink($output);
});
