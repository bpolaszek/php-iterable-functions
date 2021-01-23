<?php

declare(strict_types=1);

namespace BenTools\IterableFunctions\Tests;

use SplFixedArray;

use function BenTools\IterableFunctions\iterable_map;
use function BenTools\IterableFunctions\iterable_to_array;
use function it;
use function PHPUnit\Framework\assertEquals;

it('maps an array', function (): void {
    $iterable = ['foo', 'bar'];
    $map = 'strtoupper';
    assertEquals(['FOO', 'BAR'], iterable_to_array(iterable_map($iterable, $map)));
});

it('maps a Traversable object', function (): void {
    $iterable = SplFixedArray::fromArray(['foo', 'bar']);
    $map = 'strtoupper';
    assertEquals(['FOO', 'BAR'], iterable_to_array(iterable_map($iterable, $map)));
});
