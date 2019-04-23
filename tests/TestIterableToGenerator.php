<?php

use PHPUnit\Framework\TestCase;

class TestIterableToGenerator extends TestCase
{
    protected function setUp()
    {
        if (version_compare(PHP_VERSION, '5.5') < 0) {
            $this->markTestSkipped('generators are only available in PHP versions >= 5.5');
        }
    }

    public function testFunctionExists()
    {
        $this->assertTrue(function_exists('iterable_to_generator'));
    }

    public function testIteratorToGenerator()
    {
        $iterator = new ArrayIterator(array('foo' => 'bar'));
        $generator = iterable_to_generator($iterator);
        $this->assertInstanceOf('Generator', $generator);
        $this->assertEquals(array('foo' => 'bar'), iterable_to_array($generator));
    }

    public function testArrayToGenerator()
    {
        $array = array('foo' => 'bar');
        $generator = iterable_to_generator($array);
        $this->assertInstanceOf('Generator', $generator);
        $this->assertEquals(array('foo' => 'bar'), iterable_to_array($generator));
    }

    public function testGeneratorToGenerator()
    {
        $generator = iterable_to_generator(iterable_to_generator(array('foo' => 'bar')));
        $this->assertInstanceOf('Generator', $generator);
        $this->assertEquals(array('foo' => 'bar'), iterable_to_array($generator));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidArgument()
    {
        $gen = iterable_to_generator('foo');
        $gen->valid();
    }
}
