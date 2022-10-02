<?php

declare(strict_types=1);

namespace BenTools\IterableFunctions;

use AppendIterator;
use ArrayIterator;
use CallbackFilterIterator;
use Iterator;
use IteratorAggregate;
use IteratorIterator;
use Traversable;

use function array_filter;
use function array_map;
use function iterator_to_array;

/**
 * @internal
 *
 * @template TKey
 * @template TValue
 *
 * @implements IteratorAggregate<TKey, TValue>
 */
final class IterableObject implements IteratorAggregate
{
    /** @var iterable<TKey, TValue> */
    private $iterable;

    /** @var bool */
    private $preserveKeys;

    /** @param iterable<TKey, TValue> $iterable */
    public function __construct(iterable $iterable, bool $preserveKeys = true)
    {
        $this->iterable = $iterable;
        $this->preserveKeys = $preserveKeys;
    }

    /**
     * @param (callable(TValue):bool)|null $filter
     *
     * @return self<TKey, TValue>
     */
    public function filter(?callable $filter = null): self
    {
        if ($this->iterable instanceof Traversable) {
            $filter ??=
                /** @param mixed $value */
                static function ($value): bool {
                    return (bool) $value;
                };

            return new self(new CallbackFilterIterator(new IteratorIterator($this->iterable), $filter));
        }

        $filtered = $filter === null ? array_filter($this->iterable) : array_filter($this->iterable, $filter);

        return new self($filtered);
    }

    /**
     * @param callable(TValue):TResult $mapper
     *
     * @return self<TKey, TResult>
     *
     * @template TResult
     */
    public function map(callable $mapper): self
    {
        if ($this->iterable instanceof Traversable) {
            return new self(new MappedTraversable($this->iterable, $mapper));
        }

        return new self(array_map($mapper, $this->iterable));
    }

    /**
     * @param iterable<TKey, TValue> ...$args
     *
     * @return self<TKey, TValue>
     */
    public function merge(iterable ...$args): self
    {
        if ($args === []) {
            return $this;
        }

        $toIterator = static function (iterable $iterable): Iterator {
            if ($iterable instanceof Traversable) {
                return new IteratorIterator($iterable);
            }

            return new ArrayIterator($iterable);
        };

        $iterator = new AppendIterator();
        $iterator->append($toIterator($this->iterable));

        foreach ($args as $iterable) {
            $iterator->append($toIterator($iterable));
        }

        return new self($iterator, false);
    }

    /**
     * @return self<int, TValue>
     */
    public function values(): self
    {
        return new self(new WithoutKeysTraversable($this->iterable));
    }

    /** @return Traversable<TKey, TValue> */
    public function getIterator(): Traversable
    {
        yield from $this->iterable;
    }

    /** @return array<array-key, TValue> */
    public function asArray(): array
    {
        return $this->iterable instanceof Traversable ? iterator_to_array($this->iterable, $this->preserveKeys) : $this->iterable;
    }
}
