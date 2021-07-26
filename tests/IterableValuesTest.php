<?php

declare(strict_types=1);

namespace BenTools\IterableFunctions\Tests;

use ArrayIterator;
use PHPUnit\Framework\Assert;

use function BenTools\IterableFunctions\iterable_values;
use function it;
use function PHPUnit\Framework\assertSame;

it(
    'gets only values of array',
    function (): void {
        $iterable = ['b' => true];

        // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedForeach
        foreach (iterable_values($iterable) as $key => $value) {
        }

        if (! isset($key, $value)) {
            Assert::fail('No values were returned');
        }

        assertSame(0, $key);
        assertSame(true, $value);
    }
);

it(
    'gets values of Traversable object',
    function (): void {
        $iterable = new ArrayIterator(['b' => true]);

        // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedForeach
        foreach (iterable_values($iterable) as $key => $value) {
        }

        if (! isset($key, $value)) {
            Assert::fail('No values were returned');
        }

        assertSame(0, $key);
        assertSame(true, $value);
    }
);
