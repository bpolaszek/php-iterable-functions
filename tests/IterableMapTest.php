<?php

namespace BenTools\IterableFunctions\Tests;

use SplFixedArray;
use function BenTools\IterableFunctions\iterable_map;
use function BenTools\IterableFunctions\iterable_to_array;
use function PHPUnit\Framework\assertEquals;

it('maps an array', function () {
    $iterable = ['foo', 'bar'];
    $map = 'strtoupper';
    assertEquals(['FOO', 'BAR'], iterable_to_array(iterable_map($iterable, $map)));
});

it('maps a Traversable object', function () {
    $iterable = SplFixedArray::fromArray(['foo', 'bar']);
    $map = 'strtoupper';
    assertEquals(['FOO', 'BAR'], iterable_to_array(iterable_map($iterable, $map)));
});
