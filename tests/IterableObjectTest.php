<?php

declare(strict_types=1);

namespace BenTools\IterableFunctions\Tests;

use BenTools\IterableFunctions\IterableObject;
use Generator;
use IteratorAggregate;
use Traversable;

use function array_values;
use function BenTools\CartesianProduct\cartesian_product;
use function BenTools\IterableFunctions\iterable;
use function is_callable;
use function it;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertSame;
use function strtolower;
use function strtoupper;

$combinations = cartesian_product([
    'input' => [
        null,
        ['', 'foo', 'bar'],
        new class implements IteratorAggregate {
            /** @return Traversable<string> */
            public function getIterator(): Traversable
            {
                yield '';
                yield 'foo';
                yield 'bar';
            }
        },
    ],
    'mapper' => [
        null,
        static function (): callable {
            return static function (string $value): string {
                return strtoupper($value);
            };
        },
    ],
    'filtered' => [
        false,
        true,
    ],
    'filter' => [
        null,
        static function (): callable {
            return static function (string $value): bool {
                return strtolower($value) === 'bar';
            };
        },
    ],
]);


it(
    'produces the expected result',
    function (?iterable $input, ?callable $mapper, bool $filtered, ?callable $filter): void {
        $iterable = iterable($input);

        if ($input === null) {
            assertSame([], $iterable->asArray());

            return;
        }

        // Default expectation
        $expected = ['', 'foo', 'bar'];

        // Expectation when iterable is mapped
        if ($mapper !== null) {
            $iterable = $iterable->map($mapper);
            $expected = ['', 'FOO', 'BAR'];
        }

        // Expectation when iterable is filtered
        if ($filtered === true) {
            $iterable = $iterable->filter($filter);

            // empty string should be removed when iterable is filtered without callable
            unset($expected[0]);

            // empty string and "foo" should be removed otherwise
            if (is_callable($filter)) {
                unset($expected[1]);
            }
        }

        assertSame($expected, $iterable->asArray());
    }
)->with($combinations);

it('can filter first, then map', function (iterable $input): void {
    $map =
        /** @return mixed */
        static function (string $value) {
            $map = ['zero' => 0, 'one' => 1, 'two' => 2];

            return $map[$value];
        };

    $iterableObject = iterable($input)->filter()->map($map);
    assertInstanceOf(IterableObject::class, $iterableObject);
    assertEquals([0, 1, 2], array_values($iterableObject->asArray()));
})->with(function (): Generator {
    $input = ['zero', 'one', 'two'];
    yield [$input];
    yield [
        /** @return Generator<string> */
        (static function (array $input): Generator {
            yield from $input;
        })($input),
    ];
});

it('strips key through values()', function (): void {
    $input = ['x' => 'zero', 'y' => 'one', 'z' => 'two'];

    $iterableObject = iterable($input)->values();
    assertInstanceOf(IterableObject::class, $iterableObject);
    assertEquals(['zero', 'one', 'two'], $iterableObject->asArray());
});

it('merge iterators', function (iterable $input, array $mergeInputs, array $expected): void {
    $iterableObject = iterable($input)->merge(...$mergeInputs);
    assertInstanceOf(IterableObject::class, $iterableObject);
    assertEquals($expected, $iterableObject->asArray());
})->with(function (): Generator {
    yield 'no merge arg return initial iterable' => [['zero', 'one', 'two'], [], ['zero', 'one', 'two']];
    yield 'all values merged' => [
        ['zero', 'one', 'two'],
        [
            ['three', 'four', 'five'],
        ],
        ['zero', 'one', 'two', 'three', 'four', 'five'],
    ];
    yield 'merge all args into a new iterable' => [
        [0 => 'zero', 1 => 'one', 2 => 'two'],
        [
            [3 => 'three', 4 => 'four'],
            [5 => 'five', 6 => 'six', 7 => 'seven'],
        ],
        [0 => 'zero', 1 => 'one', 2 => 'two', 3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six', 7 => 'seven'],
    ];
});
