<?php

it('check return positive int with a fopen real file resource', function () {
    $temporaryPath = sys_get_temp_dir() . "/" . uniqid();
    $file = fopen($temporaryPath, 'a');
    try {
        expect(fileno($file))->toBeInt()->toBeGreaterThanOrEqual(0);
    } finally {
        unlink($temporaryPath);
    }
});

it('check return -1 on a non stream variable', function () {
    expect(fileno("string"))->toBeNull();
});

it('check that standard stream return correct value', function () {
    expect(fileno(STDIN))->toBe(0)
        ->and(fileno(STDOUT))->toBe(1)
        ->and(fileno(STDERR))->toBe(2);
});

it('check return -1 on a not file resource', function () {
    $memoryStream = fopen('php://memory', 'r');
    expect(fileno($memoryStream))->toBeNull();
});