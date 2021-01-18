<?php

use PHPUnit\Framework\TestCase;

final class IterableFilterTest extends TestCase
{

    public function testArrayFilter()
    {
        $iterable = array('foo', 'bar');
        $filter = function ($input) {
            return 'bar' === $input;
        };
        $this->assertEquals(array(1 => 'bar'), iterable_to_array(iterable_filter($iterable, $filter)));
    }

    public function testTraversableFilter()
    {
        $iterable = SplFixedArray::fromArray(array('foo', 'bar'));
        $filter = function ($input) {
            return 'bar' === $input;
        };
        $this->assertEquals(array(1 => 'bar'), iterable_to_array(iterable_filter($iterable, $filter)));
    }

    public function testInvalidIterable()
    {
        $this->expectException(InvalidArgumentException::class);

        $filter = function () {
            return true;
        };
        iterable_filter('foo', $filter);
    }
}
