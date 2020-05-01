<?php

use PHPUnit\Framework\TestCase;

final class IterableToTraversableTest extends TestCase
{

    public function testFunctionExists()
    {
        $this->assertTrue(function_exists('iterable_to_traversable'));
    }

    public function testIteratorToTraversable()
    {
        $iterator = new ArrayIterator(array('foo' => 'bar'));
        $traversable = iterable_to_traversable($iterator);
        $this->assertSame($iterator, $traversable);
        $this->assertInstanceOf('Traversable', $iterator);
    }

    public function testArrayToTraversable()
    {
        $array = array('foo' => 'bar');
        $traversable = iterable_to_traversable($array);
        $this->assertEquals(new ArrayIterator(array('foo' => 'bar')), $traversable);
        $this->assertInstanceOf('Traversable', $traversable);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidArgument()
    {
        $string = 'foo';
        iterable_to_traversable($string);
        var_dump(iterable_to_traversable(array('foo', 'bar')));
    }
}
