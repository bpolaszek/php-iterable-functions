<?php

use PHPUnit\Framework\TestCase;
use function BenTools\IterableFunctions\iterable_map;
use function BenTools\IterableFunctions\iterable_to_array;

final class IterableMapTest extends TestCase
{
    public function testArrayMap(): void
    {
        $iterable = ['foo', 'bar'];
        $map = 'strtoupper';
        $this->assertEquals(['FOO', 'BAR'], iterable_to_array(iterable_map($iterable, $map)));
    }

    public function testTraversableMap(): void
    {
        $iterable = SplFixedArray::fromArray(['foo', 'bar']);
        $map = 'strtoupper';
        $this->assertEquals(['FOO', 'BAR'], iterable_to_array(iterable_map($iterable, $map)));
    }
}