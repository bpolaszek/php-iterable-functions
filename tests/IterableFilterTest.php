<?php

namespace BenTools\IterableFunctions\Tests;

use SplFixedArray;
use function BenTools\IterableFunctions\iterable_filter;
use function BenTools\IterableFunctions\iterable_to_array;
use function PHPUnit\Framework\assertEquals;

it('filters an array', function () {
    $iterable = [false, true];
    assertEquals([1 => true], iterable_to_array(iterable_filter($iterable)));
});

it('filters a Travsersable object', function () {
    $iterable = SplFixedArray::fromArray([false, true]);
    assertEquals([1 => true], iterable_to_array(iterable_filter($iterable)));
});

it('filters an array with a callback', function () {
    $iterable = ['foo', 'bar'];
    $filter = static function ($input) {
        return 'bar' === $input;
    };
    assertEquals([1 => 'bar'], iterable_to_array(iterable_filter($iterable, $filter)));
});

it('filters a Travsersable object with a callback', function () {
    $iterable = SplFixedArray::fromArray(['foo', 'bar']);
    $filter = static function ($input) {
        return 'bar' === $input;
    };
    assertEquals([1 => 'bar'], iterable_to_array(iterable_filter($iterable, $filter)));
});
