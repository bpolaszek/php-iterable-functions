<?php

namespace BenTools\IterableFunctions;

use EmptyIterator;
use IteratorAggregate;
use Traversable;

/**
 * @internal
 */
final class IterableObject implements IteratorAggregate
{
    /**
     * @var iterable|array|Traversable
     */
    private $iterable;

    /**
     * @var callable
     */
    private $filterFn;

    /**
     * @var callable
     */
    private $mapFn;

    public function __construct(?iterable $iterable = null, ?callable $filter = null, ?callable $map = null)
    {
        $this->iterable = $iterable ?? new EmptyIterator();
        $this->filterFn = $filter;
        $this->mapFn = $map;
    }

    public function filter(callable $filter): self
    {
        return new self($this->iterable, $filter, $this->mapFn);
    }

    public function map(callable $map): self
    {
        return new self($this->iterable, $this->filterFn, $map);
    }

    public function getIterator(): Traversable
    {
        $iterable = $this->iterable;
        if (null !== $this->filterFn) {
            $iterable = iterable_filter($iterable, $this->filterFn);
        }
        if (null !== $this->mapFn) {
            $iterable = iterable_map($iterable, $this->mapFn);
        }
        return iterable_to_traversable($iterable);
    }

    public function asArray(): array
    {
        $iterable = $this->iterable;
        if (null !== $this->filterFn) {
            $iterable = iterable_filter($iterable, $this->filterFn);
        }
        if (null !== $this->mapFn) {
            $iterable = iterable_map($iterable, $this->mapFn);
        }
        return iterable_to_array($iterable);
    }
}
