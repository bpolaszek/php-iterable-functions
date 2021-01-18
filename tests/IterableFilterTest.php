<?php

use PHPUnit\Framework\TestCase;
use function BenTools\IterableFunctions\iterable_filter;
use function BenTools\IterableFunctions\iterable_to_array;

final class IterableFilterTest extends TestCase
{
    public function testArrayFilter(): void
    {
        $iterable = ['foo', 'bar'];
        $filter = static function ($input) {
            return 'bar' === $input;
        };
        $this->assertEquals([1 => 'bar'], iterable_to_array(iterable_filter($iterable, $filter)));
    }

    public function testTraversableFilter(): void
    {
        $iterable = SplFixedArray::fromArray(['foo', 'bar']);
        $filter = static function ($input) {
            return 'bar' === $input;
        };
        $this->assertEquals([1 => 'bar'], iterable_to_array(iterable_filter($iterable, $filter)));
    }
}
