<?php

declare(strict_types=1);

namespace BenTools\IterableFunctions\Tests;

use SplFixedArray;

use function BenTools\IterableFunctions\iterable_filter;
use function BenTools\IterableFunctions\iterable_to_array;
use function it;
use function PHPUnit\Framework\assertEquals;

it('filters an array', function (): void {
    $iterable = [false, true];
    assertEquals([1 => true], iterable_to_array(iterable_filter($iterable)));
});

it('filters a Travsersable object', function (): void {
    $iterable = SplFixedArray::fromArray([false, true]);
    assertEquals([1 => true], iterable_to_array(iterable_filter($iterable)));
});

it('filters an array with a callback', function (): void {
    $iterable = ['foo', 'bar'];
    $filter = static function ($input) {
        return $input === 'bar';
    };
    assertEquals([1 => 'bar'], iterable_to_array(iterable_filter($iterable, $filter)));
});

it('filters a Travsersable object with a callback', function (): void {
    $iterable = SplFixedArray::fromArray(['foo', 'bar']);
    $filter = static function ($input) {
        return $input === 'bar';
    };
    assertEquals([1 => 'bar'], iterable_to_array(iterable_filter($iterable, $filter)));
});
