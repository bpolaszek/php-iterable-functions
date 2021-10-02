<?php

declare(strict_types=1);

namespace BenTools\IterableFunctions\Tests;

use SplFixedArray;

use function BenTools\IterableFunctions\iterable_reduce;
use function it;
use function PHPUnit\Framework\assertSame;

it('reduces an array', function (): void {
    $iterable = [1, 2];
    $reduce = static function (?int $carry, int $item): int {
        return (int) $carry + $item;
    };
    assertSame(3, iterable_reduce($iterable, $reduce, 0));
});

it('reduces an traversable', function (): void {
    $iterable = SplFixedArray::fromArray([1, 2]);
    $reduce = static function (?int $carry, int $item): int {
        return (int) $carry + $item;
    };
    assertSame(3, iterable_reduce($iterable, $reduce, 0)); // @phpstan-ignore-line
});
