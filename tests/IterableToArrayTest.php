<?php

declare(strict_types=1);

namespace BenTools\IterableFunctions\Tests;

use ArrayIterator;

use function BenTools\IterableFunctions\iterable_to_array;
use function it;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertSame;

it('converts an iterator to an array', function (): void {
    $iterator = new ArrayIterator(['foo', 'bar']);
    assertEquals(['foo', 'bar'], iterable_to_array($iterator));
});

it('converts an iterator to an array, without keys', function (): void {
    $iterator = new ArrayIterator([1 => 'foo', 2 => 'bar']);
    assertEquals([0 => 'foo', 1 => 'bar'], iterable_to_array($iterator, false));
});

it('keeps the same array', function (): void {
    $array = ['foo', 'bar'];
    assertSame(['foo', 'bar'], iterable_to_array($array));
});

it('removes the keys of an array', function (): void {
     $array = [1 => 'foo', 2 => 'bar'];
     assertEquals([0 => 'foo', 1 => 'bar'], iterable_to_array($array, false));
});
