<?php

use BenTools\IterableFunctions\IterableObject;
use PHPUnit\Framework\TestCase;
use function BenTools\IterableFunctions\iterable;

final class IterableObjectTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testFromArrayToIterator($data, $filter = null, $map = null, $expectedResult): void
    {
        $iterableObject = iterable($data, $filter, $map);
        $this->assertInstanceOf(IterableObject::class, $iterableObject);
        $this->assertEquals($expectedResult, iterator_to_array($iterableObject));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testFromArrayToArray($data, $filter = null, $map = null, $expectedResult): void
    {
        $iterableObject = iterable($data, $filter, $map);
        $this->assertInstanceOf(IterableObject::class, $iterableObject);
        $this->assertEquals($expectedResult, $iterableObject->asArray());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testFromTraversableToIterator($data, $filter = null, $map = null, $expectedResult): void
    {
        $data = SplFixedArray::fromArray($data);
        $iterableObject = iterable($data, $filter, $map);
        $this->assertInstanceOf(IterableObject::class, $iterableObject);
        $this->assertEquals($expectedResult, iterator_to_array($iterableObject));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testFromTraversableToArray($data, $filter = null, $map = null, $expectedResult): void
    {
        $data = SplFixedArray::fromArray($data);
        $iterableObject = iterable($data, $filter, $map);
        $this->assertInstanceOf(IterableObject::class, $iterableObject);
        $this->assertEquals($expectedResult, $iterableObject->asArray());
    }

    public function dataProvider()
    {
        $data = ['foo', 'bar'];
        $filter = static function ($value) {
            return 'bar' === $value;
        };
        $map = 'strtoupper';

        return [
            [
                $data,
                null,
                null,
                ['foo', 'bar'],
            ],
            [
                $data,
                $filter,
                null,
                [1 => 'bar'],
            ],
            [
                $data,
                null,
                $map,
                ['FOO', 'BAR'],
            ],
            [
                $data,
                $filter,
                $map,
                [1 => 'BAR'],
            ],
        ];
    }

}
