<?php

declare(strict_types=1);

namespace BenTools\IterableFunctions;

use EmptyIterator;
use IteratorAggregate;
use Traversable;

/**
 * @internal
 *
 * @implements IteratorAggregate<mixed>
 */
final class IterableObject implements IteratorAggregate
{
    /** @var iterable<mixed> */
    private $iterable;

    /** @var callable|null */
    private $filterFn;

    /** @var callable|null */
    private $mapFn;

    /**
     * @param iterable<mixed>|null $iterable
     */
    private function __construct(?iterable $iterable = null, ?callable $filter = null, ?callable $map = null)
    {
        $this->iterable = $iterable ?? new EmptyIterator();
        $this->filterFn = $filter;
        $this->mapFn = $map;
    }

    /**
     * @param iterable<mixed>|null $iterable
     */
    public static function new(?iterable $iterable = null): self
    {
        return new self($iterable);
    }

    public function filter(?callable $filter = null): self
    {
        $filter = $filter ?? function ($value): bool {
            return (bool) $value;
        };

        return new self($this->iterable, $filter, $this->mapFn);
    }

    public function map(callable $map): self
    {
        return new self($this->iterable, $this->filterFn, $map);
    }

    /**
     * @return Traversable<mixed>
     */
    public function getIterator(): Traversable
    {
        $iterable = $this->iterable;
        if ($this->filterFn !== null) {
            $iterable = iterable_filter($iterable, $this->filterFn);
        }

        if ($this->mapFn !== null) {
            $iterable = iterable_map($iterable, $this->mapFn);
        }

        return iterable_to_traversable($iterable);
    }

    /** @return array<mixed> */
    public function asArray(): array
    {
        $iterable = $this->iterable;
        if ($this->filterFn !== null) {
            $iterable = iterable_filter($iterable, $this->filterFn);
        }

        if ($this->mapFn !== null) {
            $iterable = iterable_map($iterable, $this->mapFn);
        }

        return iterable_to_array($iterable);
    }
}
