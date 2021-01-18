<?php

declare(strict_types=1);

namespace BenTools\IterableFunctions\Tests;

use PHPUnit\Framework\TestCase;
use SplFixedArray;

use function BenTools\IterableFunctions\iterable;
use function iterator_to_array;

final class IterableObjectTest extends TestCase
{
    /**
     * @param array<mixed> $data
     * @param array<mixed> $expectedResult
     *
     * @dataProvider dataProvider
     */
    public function testFromArrayToIterator(array $data, ?callable $filter, ?string $map, array $expectedResult): void
    {
        $iterableObject = iterable($data, $filter, $map);
        $this->assertEquals($expectedResult, iterator_to_array($iterableObject));
    }

    /**
     * @param array<mixed> $data
     * @param array<mixed> $expectedResult
     *
     * @dataProvider dataProvider
     */
    public function testFromArrayToArray(array $data, ?callable $filter, ?string $map, array $expectedResult): void
    {
        $iterableObject = iterable($data, $filter, $map);
        $this->assertEquals($expectedResult, $iterableObject->asArray());
    }

    /**
     * @param array<mixed> $data
     * @param array<mixed> $expectedResult
     *
     * @dataProvider dataProvider
     */
    public function testFromTraversableToIterator(array $data, ?callable $filter, ?string $map, array $expectedResult): void
    {
        $data = SplFixedArray::fromArray($data);
        $iterableObject = iterable($data, $filter, $map);
        $this->assertEquals($expectedResult, iterator_to_array($iterableObject));
    }

    /**
     * @param array<mixed> $data
     * @param array<mixed> $expectedResult
     *
     * @dataProvider dataProvider
     */
    public function testFromTraversableToArray(array $data, ?callable $filter, ?string $map, array $expectedResult): void
    {
        $data = SplFixedArray::fromArray($data);
        $iterableObject = iterable($data, $filter, $map);
        $this->assertEquals($expectedResult, $iterableObject->asArray());
    }

    /**
     * @return list<array{array<mixed>, callable|null, string|null, array<mixed>}>>
     */
    public function dataProvider(): array
    {
        $data = ['foo', 'bar'];
        $filter = static function ($value) {
            return $value === 'bar';
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
