<?php

declare(strict_types=1);

namespace BenTools\IterableFunctions;

use Iterator;
use IteratorIterator;
use Traversable;

/**
 * @internal
 *
 * @template TKey
 * @template TValue
 *
 * @implements Iterator<int, array<TKey, TValue>>
 */
final class ChunkIterator implements Iterator
{
    /** @var Iterator<TKey, TValue> */
    private Iterator $iterator;

    private int $chunkSize;

    private bool $preserveKeys;

    private int $chunkIndex = 0;

    /** @var array<TKey, TValue> */
    private array $buffer = [];

    /**
     * @param Traversable<TKey, TValue> $iterator
     */
    public function __construct(
        Traversable $iterator,
        int $chunkSize,
        bool $preserveKeys = false,
    ) {
        $this->iterator = $iterator instanceof Iterator ? $iterator : new IteratorIterator($iterator);
        $this->chunkSize = $chunkSize;
        $this->preserveKeys = $preserveKeys;
    }

    public function current(): mixed
    {
        return $this->buffer;
    }

    public function next(): void
    {
        $this->fill();
        $this->chunkIndex++;
    }

    public function key(): int
    {
        return $this->chunkIndex;
    }

    public function valid(): bool
    {
        if ($this->chunkIndex === 0) {
            $this->fill();
        }

        return $this->buffer !== [];
    }

    public function rewind(): void
    {
        $this->iterator->rewind();
        $this->chunkIndex = 0;
        $this->buffer = [];
    }

    private function fill(): void
    {
        $this->buffer = [];
        $i = 0;
        while ($this->iterator->valid() && $i++ < $this->chunkSize) {
            $current = $this->iterator->current();

            if ($this->preserveKeys) {
                $this->buffer[$this->iterator->key()] = $current;
            } else {
                $this->buffer[] = $current;
            }

            $this->iterator->next();
        }
    }
}
