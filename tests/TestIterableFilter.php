<?php

class TestIterableFilter extends \PHPUnit\Framework\TestCase
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

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidIterable()
    {
        $filter = function () {
            return true;
        };
        iterable_filter('foo', $filter);
    }
}