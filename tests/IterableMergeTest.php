<?php

declare(strict_types=1);

namespace BenTools\IterableFunctions\Tests;

use Generator;
use SplFixedArray;

use function array_merge;
use function BenTools\IterableFunctions\iterable;
use function BenTools\IterableFunctions\iterable_merge;
use function BenTools\IterableFunctions\iterable_to_array;
use function it;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertSame;

it('can be called without parameters', function (): void {
    /** @phpstan-ignore-next-line */
    assertEquals(iterable(null), iterable_merge());
});

it('merges an array', function (): void {
    $iterable = [0 => 'zero', 1 => 'one'];
    $array = [2 => 'two'];
    assertSame([0 => 'zero', 1 => 'one', 2 => 'two'], iterable_to_array(iterable_merge($iterable, $array)));
});

it('merges multiples values', function (): void {
    $iterable = [0 => 'zero', 1 => 'one'];
    $array1 = [2 => 'two'];
    $array2 = [3 => 'three', 4 => 'four'];
    assertSame(
        [0 => 'zero', 1 => 'one', 2 => 'two', 3 => 'three', 4 => 'four'],
        iterable_to_array(iterable_merge($iterable, $array1, $array2)),
    );
});

it('merges a Traversable object', function (): void {
    $iterable = SplFixedArray::fromArray([0 => 'zero', 1 => 'one']);
    $array = [2 => 'two'];
    $merged = iterable_merge($iterable, $array);
    assertSame([0 => 'zero', 1 => 'one', 2 => 'two'], iterable_to_array($merged));
});

it('iterable_merge should be equals to array_merge result', function (): void {
    $array1 = ['bar', 'baz'];
    $array2 = ['bat', 'baz'];
    /** @var callable(): Generator<int, string> $generator1 */
    $generator1 = fn () => yield from $array1;
    /** @var callable(): Generator<int, string> $generator2 */
    $generator2 = fn () => yield from $array2;

    assertSame(
        array_merge($array1, $array2),
        iterable_merge($generator1(), $generator2())->asArray(),
    );
})->with(function (): Generator {
    yield 'simple array' => [
        ['bar', 'baz'],
        ['bat', 'baz'],
    ];

    yield 'associative array' => [
        ['key1' => 'bar', 'key2' => 'baz'],
        ['key3' => 'bat', 'key4' => 'baz'],
    ];

    yield 'associative array with duplicate keys' => [
        ['bar' => 'bar', 'baz' => 'baz'],
        ['bat' => 'bat', 'baz' => 'baz'],
    ];
});
