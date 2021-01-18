<?php

namespace BenTools\IterableFunctions\Tests;

use SplFixedArray;
use function BenTools\IterableFunctions\iterable_reduce;
use function PHPUnit\Framework\assertSame;

it('reduces an array', function () {
    $iterable = [1, 2];
    $reduce = static function ($carry, $item) {
        return $carry + $item;
    };
    assertSame(3, iterable_reduce($iterable, $reduce, 0));
});

it('reduces an traversable', function () {
    $iterable = SplFixedArray::fromArray([1, 2]);
    $reduce = static function ($carry, $item) {
        return $carry + $item;
    };
    assertSame(3, iterable_reduce($iterable, $reduce, 0));
});
