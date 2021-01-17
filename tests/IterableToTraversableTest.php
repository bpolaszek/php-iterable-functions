<?php

use PHPUnit\Framework\TestCase;
use function BenTools\IterableFunctions\iterable_to_traversable;

final class IterableToTraversableTest extends TestCase
{
    public function testIteratorToTraversable(): void
    {
        $iterator = new ArrayIterator(['foo' => 'bar']);
        $traversable = iterable_to_traversable($iterator);
        $this->assertSame($iterator, $traversable);
    }

    public function testArrayToTraversable(): void
    {
        $array = ['foo' => 'bar'];
        $traversable = iterable_to_traversable($array);
        $this->assertEquals(new ArrayIterator(['foo' => 'bar']), $traversable);
    }

    public function testInvalidArgument(): void
    {
        $this->expectException(TypeError::class);

        $string = 'foo';
        iterable_to_traversable($string);
    }
}
