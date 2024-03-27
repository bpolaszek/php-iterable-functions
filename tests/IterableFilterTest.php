<?php

declare(strict_types=1);

namespace BenTools\IterableFunctions\Tests;

use Generator;
use SplFixedArray;
use Traversable;

use function assert;
use function BenTools\IterableFunctions\iterable_filter;
use function BenTools\IterableFunctions\iterable_to_array;
use function it;
use function iterator_to_array;
use function PHPUnit\Framework\assertSame;

it('filters an array', function (): void {
    $iterable = [false, true];
    assertSame([1 => true], iterable_to_array(iterable_filter($iterable)));
});

it('filters a Traversable object', function (): void {
    $iterable = SplFixedArray::fromArray([false, true]);
    $filtered = iterable_filter($iterable);
    assert($filtered instanceof Traversable);
    assertSame([1 => true], iterator_to_array($filtered));
});

it('filters an array with a callback', function (): void {
    $iterable = ['foo', 'bar'];
    $filter =
    /** @param mixed $input */
    static function ($input): bool {
        return $input === 'bar';
    };
    assertSame([1 => 'bar'], iterable_to_array(iterable_filter($iterable, $filter)));
});

it('filters a Travsersable object with a callback', function (): void {
    $iterable = SplFixedArray::fromArray(['foo', 'bar']);
    $filter =
    /** @param mixed $input */
    static function ($input): bool {
        return $input === 'bar';
    };
    $filtered = iterable_filter($iterable, $filter);
    assert($filtered instanceof Traversable);
    assertSame([1 => 'bar'], iterator_to_array($filtered));
});

it('filters a Traversable object with a callback using key', function (): void {
    $iterable = (function (): Generator {
        yield 'foo' => 1;
        yield 'bar' => 1;
    })();
    $filter =
    static function (int $input, string $key): bool {
        return $key === 'bar';
    };
    $filtered = iterable_filter($iterable, $filter);
    assert($filtered instanceof Traversable);
    assertSame(['bar' => 1], iterator_to_array($filtered));
});

it('filters an array with a callback using key', function (): void {
    $iterable = ['foo' => 1, 'bar' => 1];
    $filter =
    static function (int $input, string $key): bool {
        return $key === 'bar';
    };
    $filtered = iterable_filter($iterable, $filter);
    assert($filtered instanceof Traversable);
    assertSame(['bar' => 1], iterator_to_array($filtered));
});
