<?php

declare(strict_types=1);

namespace BenTools\IterableFunctions\Tests;

use function BenTools\IterableFunctions\iterable_chunk;
use function expect;
use function it;

it('chunks an iterable', function (iterable $fruits): void {
    $chunks = iterable_chunk($fruits, 2);
    $expectedChunks = [
        ['banana', 'apple'],
        ['strawberry', 'raspberry'],
        ['pineapple'],
    ];
    expect([...$chunks])->toEqual($expectedChunks);
})->with(function () {
    $fruits = [
        'banana',
        'apple',
        'strawberry',
        'raspberry',
        'pineapple',
    ];
    yield 'array' => [$fruits];
    yield 'traversable' => [(fn () => yield from $fruits)()];
});

it('preserves keys', function (iterable $fruits): void {
    $chunks = iterable_chunk($fruits, 2, true);
    $expectedChunks = [
        [
            'banana' => 0,
            'apple' => 1,
        ],
        [
            'strawberry' => 2,
            'raspberry' => 3,
        ],
        ['pineapple' => 4],
    ];
    expect([...$chunks])->toEqual($expectedChunks);
})->with(function () {
    $fruits = [
        'banana' => 0,
        'apple' => 1,
        'strawberry' => 2,
        'raspberry' => 3,
        'pineapple' => 4,
    ];
    yield 'array' => [$fruits];
    yield 'traversable' => [(fn () => yield from $fruits)()];
});
