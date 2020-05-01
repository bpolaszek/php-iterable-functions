<?php

use PHPUnit\Framework\TestCase;

final class IterableMapTest extends TestCase
{

    public function testArrayMap()
    {
        $iterable = array('foo', 'bar');
        $map = 'strtoupper';
        $this->assertEquals(array('FOO', 'BAR'), iterable_to_array(iterable_map($iterable, $map)));
    }

    public function testTraversableMap()
    {
        $iterable = SplFixedArray::fromArray(array('foo', 'bar'));
        $map = 'strtoupper';
        $this->assertEquals(array('FOO', 'BAR'), iterable_to_array(iterable_map($iterable, $map)));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidIterable()
    {
        $filter = function () {
            return true;
        };
        iterable_map('foo', $filter);
    }
}
