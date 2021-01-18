<?php

namespace BenTools\IterableFunctions\Tests;

use ArrayIterator;
use function BenTools\IterableFunctions\iterable_to_traversable;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertSame;

it('keeps the same traversable object', function () {
    $iterator = new ArrayIterator(['foo' => 'bar']);
    $traversable = iterable_to_traversable($iterator);
    assertSame($iterator, $traversable);
});

it('converts an array to a traversable object', function () {
    $array = ['foo' => 'bar'];
    $traversable = iterable_to_traversable($array);
    assertEquals(new ArrayIterator(['foo' => 'bar']), $traversable);
});
