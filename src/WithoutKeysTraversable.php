<?php

declare(strict_types=1);

namespace BenTools\IterableFunctions;

use Generator;
use IteratorAggregate;

/**
 * @internal
 *
 * @template TKey
 * @template TValue
 * @implements IteratorAggregate<TKey, TValue>
 */
final class WithoutKeysTraversable implements IteratorAggregate
{
    /** @var iterable<TKey, TValue> */
    private $iterable;

    /**
     * @param iterable<TKey, TValue> $iterable
     */
    public function __construct(iterable $iterable)
    {
        $this->iterable = $iterable;
    }

    /**
     * @return Generator<TValue>
     */
    public function getIterator(): Generator
    {
        foreach ($this->iterable as $value) {
            yield $value;
        }
    }
}
