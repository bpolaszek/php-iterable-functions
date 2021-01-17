<?php

use PHPUnit\Framework\TestCase;
use function BenTools\IterableFunctions\iterable_reduce;

final class IterableReduceTest extends TestCase
{
    public function testArrayReduce(): void
    {
        $iterable = array(1, 2);
        $reduce = static function ($carry, $item) {
            return $carry + $item;
        };
        self::assertSame(3, iterable_reduce($iterable, $reduce, 0));
    }

    public function testTraversableReduce(): void
    {
        $iterable = SplFixedArray::fromArray(array(1, 2));
        $reduce = static function ($carry, $item) {
            return $carry + $item;
        };
        self::assertSame(3, iterable_reduce($iterable, $reduce, 0));
    }
}
