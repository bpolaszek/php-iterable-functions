<?php

declare(strict_types=1);

namespace BenTools\IterableFunctions\Tests;

use BenTools\IterableFunctions\IterableObject;
use SplFixedArray;

use function BenTools\IterableFunctions\iterable;
use function it;
use function iterator_to_array;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertInstanceOf;
use function test;

$dataProvider = function () {
    $data = ['foo', 'bar'];
    $filter = static function ($value) {
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

test('input: array | output: traversable', function ($data, $filter, $map, $expectedResult): void {
    $iterableObject = iterable($data, $filter, $map);
    assertEquals($expectedResult, iterator_to_array($iterableObject));
})->with($dataProvider());

test('input: array | output: array', function ($data, $filter, $map, $expectedResult): void {
    $iterableObject = iterable($data, $filter, $map);
    assertEquals($expectedResult, $iterableObject->asArray());
})->with($dataProvider());

test('input: traversable | output: traversable', function ($data, $filter, $map, $expectedResult): void {
    $data = SplFixedArray::fromArray($data);
    $iterableObject = iterable($data, $filter, $map);
    assertEquals($expectedResult, iterator_to_array($iterableObject));
})->with($dataProvider());

test('input: traversable | output: array', function ($data, $filter, $map, $expectedResult): void {
    $data = SplFixedArray::fromArray($data);
    $iterableObject = iterable($data, $filter, $map);
    assertEquals($expectedResult, $iterableObject->asArray());
})->with($dataProvider());

it('filters the subject', function (): void {
    $filter = static function ($value) {
        return $value === 'bar';
    };
    $iterableObject = iterable(['foo', 'bar'])->filter($filter);
    assertEquals([1 => 'bar'], iterator_to_array($iterableObject));
});

it('maps the subject', function (): void {
    $map = 'strtoupper';
    $iterableObject = iterable(['foo', 'bar'])->map($map);
    assertInstanceOf(IterableObject::class, $iterableObject);
    assertEquals(['FOO', 'BAR'], iterator_to_array($iterableObject));
});

it('combines filter and map', function (): void {
    $filter = static function ($value) {
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
