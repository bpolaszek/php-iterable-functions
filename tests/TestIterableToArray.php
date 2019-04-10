<?php

use PHPUnit\Framework\TestCase;

class TestIterableToArray extends TestCase
{

    public function testFunctionExists()
    {
        $this->assertTrue(function_exists('iterable_to_array'));
    }

    public function testIteratorToArray()
    {
        $iterator = new ArrayIterator(array('foo', 'bar'));
        $this->assertEquals(array('foo', 'bar'), iterable_to_array($iterator));
    }

    public function testIteratorWithoutKeysToArray()
    {
        $iterator = new ArrayIterator(array(1 => 'foo', 2 => 'bar'));
        $this->assertEquals(array(0 => 'foo', 1 => 'bar'), iterable_to_array($iterator, false));
    }

    public function testArrayToArray()
    {
        $array = array('foo', 'bar');
        $this->assertEquals(array('foo', 'bar'), iterable_to_array($array));
    }

    public function testArrayWithoutKeysToArray()
    {
        $array = array(1 => 'foo', 2 => 'bar');
        $this->assertEquals(array(0 => 'foo', 1 => 'bar'), iterable_to_array($array, false));
    }

    public function testScalarToArray()
    {
        $scalar = 'foobar';
        $this->assertTrue($this->triggersError($scalar));
    }

    public function testObjectToArray()
    {
        $object = new stdClass();
        $this->assertTrue($this->triggersError($object));
    }

    public function testResourceToArray()
    {
        $resource = fopen('php://temp', 'rb');
        $this->assertTrue($this->triggersError($resource));
    }

    private function triggersError($input)
    {
        return version_compare(PHP_VERSION, '7.0.0') >= 0 ? $this->triggersErrorPHP7($input) : $this->triggersErrorPHP5($input);
    }

    private function triggersErrorPHP7($input)
    {
        $errorOccured = false;

        try {
            iterable_to_array($input);
        }
        catch (\TypeError $e) {
            $errorOccured = true;
        }

        return $errorOccured;
    }

    private function triggersErrorPHP5($input)
    {
        $errorOccured = false;

        set_error_handler(function ($errno) {
            return E_RECOVERABLE_ERROR === $errno;
        });

        if (false === @iterable_to_array($input)) {
            $errorOccured = true;
        }

        restore_error_handler();

        return $errorOccured;
    }

}
