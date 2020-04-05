<?php

class TestIterableObject extends \PHPUnit\Framework\TestCase
{

    /**
     * @dataProvider dataProvider
     */
    public function testFromArrayToIterator($data, $filter = null, $map = null, $expectedResult)
    {
        $iterableObject = iterable($data, $filter, $map);
        $this->assertInstanceOf('BenTools\IterableFunctions\IterableObject', $iterableObject);
        $this->assertEquals($expectedResult, iterator_to_array($iterableObject));
    }
    /**
     * @dataProvider dataProvider
     */
    public function testFromArrayToArray($data, $filter = null, $map = null, $expectedResult)
    {
        $iterableObject = iterable($data, $filter, $map);
        $this->assertInstanceOf('BenTools\IterableFunctions\IterableObject', $iterableObject);
        $this->assertEquals($expectedResult, $iterableObject->asArray());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testFromTraversableToIterator($data, $filter = null, $map = null, $expectedResult)
    {
        $data = SplFixedArray::fromArray($data);
        $iterableObject = iterable($data, $filter, $map);
        $this->assertInstanceOf('BenTools\IterableFunctions\IterableObject', $iterableObject);
        $this->assertEquals($expectedResult, iterator_to_array($iterableObject));
    }
    /**
     * @dataProvider dataProvider
     */
    public function testFromTraversableToArray($data, $filter = null, $map = null, $expectedResult)
    {
        $data = SplFixedArray::fromArray($data);
        $iterableObject = iterable($data, $filter, $map);
        $this->assertInstanceOf('BenTools\IterableFunctions\IterableObject', $iterableObject);
        $this->assertEquals($expectedResult, $iterableObject->asArray());
    }

    public function testFilterMutator()
    {
        $filter = function ($value) {
            return 'bar' === $value;
        };
        $iterableObject = iterable(array('foo', 'bar'))->withFilter($filter);
        $this->assertEquals(array(1 => 'bar'), iterator_to_array($iterableObject));
    }

    public function testMapMutator()
    {
        $map = 'strtoupper';
        $iterableObject = iterable(array('foo', 'bar'))->withMap($map);
        $this->assertEquals(array('FOO', 'BAR'), iterator_to_array($iterableObject));
    }

    public function testFilterAndMapMutators()
    {
        $filter = function ($value) {
            return 'bar' === $value;
        };
        $map = 'strtoupper';
        $iterableObject = iterable(array('foo', 'bar'))->withMap($map)->withFilter($filter);
        $this->assertEquals(array(1 => 'BAR'), iterator_to_array($iterableObject));
        $iterableObject = iterable(array('foo', 'bar'))->map($map)->filter($filter);
        $this->assertEquals(array(1 => 'BAR'), iterator_to_array($iterableObject));
        $iterableObject = iterable(array('foo', 'bar'))->withFilter($filter)->withMap($map);
        $this->assertEquals(array(1 => 'BAR'), iterator_to_array($iterableObject));
        $iterableObject = iterable(array('foo', 'bar'))->filter($filter)->map($map);
        $this->assertEquals(array(1 => 'BAR'), iterator_to_array($iterableObject));
    }

    public function dataProvider()
    {
        $data = array('foo', 'bar');
        $filter = function ($value) {
            return 'bar' === $value;
        };
        $map = 'strtoupper';

        return array(
            array(
                $data,
                null,
                null,
                array('foo', 'bar')
            ),
            array(
                $data,
                $filter,
                null,
                array(1 => 'bar')
            ),
            array(
                $data,
                null,
                $map,
                array('FOO', 'BAR')
            ),
            array(
                $data,
                $filter,
                $map,
                array(1 => 'BAR')
            ),
        );
    }

}
