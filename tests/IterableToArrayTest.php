<?php

namespace BenTools\IterableFunctions\Tests;

use ArrayIterator;
use function BenTools\IterableFunctions\iterable_to_array;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertSame;

it('converts an iterator to an array', function () {
    $iterator = new ArrayIterator(['foo', 'bar']);
    assertEquals(['foo', 'bar'], iterable_to_array($iterator));
});

it('converts an iterator to an array, without keys', function () {
    $iterator = new ArrayIterator([1 => 'foo', 2 => 'bar']);
    assertEquals([0 => 'foo', 1 => 'bar'], iterable_to_array($iterator, false));
});

it('keeps the same array', function () {
    $array = ['foo', 'bar'];
    assertSame(['foo', 'bar'], iterable_to_array($array));
});

it('removes the keys of an array', function () {
     $array = [1 => 'foo', 2 => 'bar'];
     assertEquals([0 => 'foo', 1 => 'bar'], iterable_to_array($array, false));
});
