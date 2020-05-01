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
