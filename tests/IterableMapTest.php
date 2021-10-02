<?php

declare(strict_types=1);

namespace BenTools\IterableFunctions\Tests;

use Generator;
use PHPUnit\Framework\Assert;
use SplFixedArray;
use stdClass;
use Traversable;

use function assert;
use function BenTools\IterableFunctions\iterable_map;
use function BenTools\IterableFunctions\iterable_to_array;
use function it;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertSame;

it('maps an array', function (): void {
    $iterable = ['foo', 'bar'];
    $map = 'strtoupper';
    assertSame(['FOO', 'BAR'], iterable_to_array(iterable_map($iterable, $map)));
});

it('maps a Traversable object', function (): void {
    $iterable = SplFixedArray::fromArray(['foo', 'bar']);
    $map = 'strtoupper';
    $mapped = iterable_map($iterable, $map); // @phpstan-ignore-line
    assert($mapped instanceof Traversable);
    assertSame(['FOO', 'BAR'], iterable_to_array($mapped));
});

it('maps iterable with object keys', function (): void {
    foreach (iterable_map(iterableWithObjectKeys(), 'strtoupper') as $key => $item) {
        assertInstanceOf(stdClass::class, $key);
        assertSame('FOO', $item);

        return;
    }

    Assert::fail('Did not iterate');
});

/** @return Generator<stdClass, string> */
function iterableWithObjectKeys(): Generator
{
    yield new stdClass() => 'foo';
}
