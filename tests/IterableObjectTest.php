<?php

declare(strict_types=1);

namespace BenTools\IterableFunctions\Tests;

use BenTools\IterableFunctions\IterableObject;
use Generator;
use SplFixedArray;

use function array_values;
use function BenTools\IterableFunctions\iterable;
use function func_num_args;
use function it;
use function iterator_to_array;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertSame;
use function test;

$dataProvider = static function (): Generator {
    $data = ['foo', 'bar'];
    $filter =
        /** @param mixed $value */
        static function ($value): bool {
            return $value === 'bar';
        };
    $map = 'strtoupper';

    yield from [
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
};

/**
 * @param iterable<mixed> $iterable
 */
function create_iterable(iterable $iterable, ?callable $filter = null, ?callable $map = null): IterableObject
{
    $object = iterable($iterable);

    if ($filter !== null && func_num_args() > 1) {
        $object = $object->filter($filter);
    }

    if ($map !== null) {
        $object = $object->map($map);
    }

    return $object;
}

test(
    'input: array | output: traversable',
    /** @param array<int, mixed> $data */
    function (array $data, ?callable $filter, ?callable $map, array $expectedResult): void {
        $iterableObject = create_iterable($data, $filter, $map);
        assertEquals($expectedResult, iterator_to_array($iterableObject));
    }
)->with($dataProvider());

test(
    'input: array | output: array',
    /** @param array<int, mixed> $data */
    function (array $data, ?callable $filter, ?callable $map, array $expectedResult): void {
        $iterableObject = create_iterable($data, $filter, $map);
        assertEquals($expectedResult, $iterableObject->asArray());
    }
)->with($dataProvider());

test(
    'input: traversable | output: traversable',
    /** @param array<int, mixed> $data */
    function (array $data, ?callable $filter, ?callable $map, array $expectedResult): void {
        $data = SplFixedArray::fromArray($data);
        $iterableObject = create_iterable($data, $filter, $map);
        assertEquals($expectedResult, iterator_to_array($iterableObject));
    }
)->with($dataProvider());

test(
    'input: traversable | output: array',
    /** @param array<int, mixed> $data */
    function (array $data, ?callable $filter, ?callable $map, array $expectedResult): void {
        $data = SplFixedArray::fromArray($data);
        $iterableObject = create_iterable($data, $filter, $map);
        assertEquals($expectedResult, $iterableObject->asArray());
    }
)->with($dataProvider());

it('does not filter by default', function (): void {
    $data = [
        null,
        false,
        true,
        0,
        1,
        '0',
        '1',
        '',
        'foo',
    ];

    $generator = function (array $data): Generator {
        yield from $data;
    };

    assertSame($data, iterable($data)->asArray());
    assertSame($data, iterable($generator($data))->asArray());
});

it('filters the subject', function (): void {
    $filter =
        /** @param mixed $value */
        static function ($value): bool {
            return $value === 'bar';
        };
    $iterableObject = iterable(['foo', 'bar'])->filter($filter);
    assertEquals([1 => 'bar'], iterator_to_array($iterableObject));
});

it('uses a truthy filter by default when filter() is invoked without arguments', function (): void {
    $data = [
        null,
        false,
        true,
        0,
        1,
        '0',
        '1',
        '',
        'foo',
    ];

    $truthyValues = [
        true,
        1,
        '1',
        'foo',
    ];

    $generator = function (array $data): Generator {
        yield from $data;
    };

    assertSame($truthyValues, array_values(iterable($data)->filter()->asArray()));
    assertSame($truthyValues, array_values(iterable($generator($data))->filter()->asArray()));
});

it('maps the subject', function (): void {
    $map = 'strtoupper';
    $iterableObject = iterable(['foo', 'bar'])->map($map);
    assertInstanceOf(IterableObject::class, $iterableObject);
    assertEquals(['FOO', 'BAR'], iterator_to_array($iterableObject));
});

it('combines filter and map', function (): void {
    $filter =
        /** @param mixed $value */
        static function ($value): bool {
            return $value === 'bar';
        };
    $map = 'strtoupper';
    $iterableObject = iterable(['foo', 'bar'])->map($map)->filter($filter);
    assertInstanceOf(IterableObject::class, $iterableObject);
    assertEquals([1 => 'BAR'], iterator_to_array($iterableObject));
    $iterableObject = iterable(['foo', 'bar'])->filter($filter)->map($map);
    assertInstanceOf(IterableObject::class, $iterableObject);
    assertEquals([1 => 'BAR'], iterator_to_array($iterableObject));
});
