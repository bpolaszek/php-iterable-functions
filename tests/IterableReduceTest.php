<?php

use PHPUnit\Framework\TestCase;

final class IterableReduceTest extends TestCase
{
    public function testArrayReduce()
    {
        $iterable = array(1, 2);
        $reduce = function ($carry, $item) {
            return $carry + $item;
        };
        self::assertSame(3, iterable_reduce($iterable, $reduce, 0));
    }

    public function testTraversableReduce()
    {
        $iterable = SplFixedArray::fromArray(array(1, 2));
        $reduce = function ($carry, $item) {
            return $carry + $item;
        };
        self::assertSame(3, iterable_reduce($iterable, $reduce, 0));
    }
}
