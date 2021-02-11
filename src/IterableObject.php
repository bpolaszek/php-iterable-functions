<?php

declare(strict_types=1);

namespace BenTools\IterableFunctions;

use CallbackFilterIterator;
use IteratorAggregate;
use IteratorIterator;
use Traversable;

use function array_filter;
use function array_map;
use function iterator_to_array;

/**
 * @internal
 *
 * @implements IteratorAggregate<mixed>
 */
final class IterableObject implements IteratorAggregate
{
    /** @var iterable<mixed> */
    private $iterable;

    /**
     * @param iterable<mixed> $iterable
     */
    public function __construct(iterable $iterable)
    {
        $this->iterable = $iterable;
    }

    public function filter(?callable $filter = null): self
    {
        if ($this->iterable instanceof Traversable) {
            $filter = $filter ??
                /** @param mixed $value */
                static function ($value): bool {
                    return (bool) $value;
                };

            return new self(new CallbackFilterIterator(new IteratorIterator($this->iterable), $filter));
        }

        $filtered = $filter === null ? array_filter($this->iterable) : array_filter($this->iterable, $filter);

        return new self($filtered);
    }

    public function map(callable $mapper): self
    {
        if ($this->iterable instanceof Traversable) {
            return new self(new MappedTraversable($this->iterable, $mapper));
        }

        return new self(array_map($mapper, $this->iterable));
    }

    /**
     * @return Traversable<mixed>
     */
    public function getIterator(): Traversable
    {
        yield from $this->iterable;
    }

    /** @return array<mixed> */
    public function asArray(): array
    {
        return $this->iterable instanceof Traversable ? iterator_to_array($this->iterable) : $this->iterable;
    }
}
