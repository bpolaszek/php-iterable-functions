<?php

use PHPUnit\Framework\TestCase;

final class IsIterableTest extends TestCase
{

    public function testFunctionExists()
    {
        $this->assertTrue(function_exists('is_iterable'));
    }

    public function testArrayIsIterable()
    {
        $array = array('foo', 'bar');
        $this->assertTrue(is_iterable($array));
    }

    public function testIteratorIsIterable()
    {
        $iterator = new DirectoryIterator(__DIR__);
        $this->assertTrue(is_iterable($iterator));
    }

    public function testScalarIsNotIterable()
    {
        $scalar = 'foobar';
        $this->assertFalse(is_iterable($scalar));
    }

    public function testObjectIsNotIterable()
    {
        $object = new \stdClass();
        $this->assertFalse(is_iterable($object));
    }

    public function testResourceIsNotIterable()
    {
        $resource = fopen('php://temp', 'rb');
        $this->assertFalse(is_iterable($resource));
    }

}
