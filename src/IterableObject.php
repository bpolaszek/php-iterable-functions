<?php

namespace BenTools\IterableFunctions;

use Closure;
use EmptyIterator;
use InvalidArgumentException;
use IteratorAggregate;
use Traversable;

final class IterableObject implements IteratorAggregate
{
    /**
     * @var iterable|array|Traversable
     */
    private $iterable;

    /**
     * @var callable
     */
    private $filter;

    /**
     * @var callable
     */
    private $map;

    /**
     * IterableObject constructor.
     * @param iterable|array|Traversable $iterable
     * @param callable|null              $filter
     * @param callable|null              $map
     * @throws InvalidArgumentException
     */
    public function __construct($iterable, $filter = null, $map = null)
    {
        if (null === $iterable) {
            $iterable = new EmptyIterator();
        }
        if (!is_iterable($iterable)) {
            throw new InvalidArgumentException(
                sprintf('Expected array or Traversable, got %s', is_object($iterable) ? get_class($iterable) : gettype($iterable))
            );
        }

        // Cannot rely on callable type-hint on PHP 5.3
        if (null !== $filter && !is_callable($filter) && !$filter instanceof Closure) {
            throw new InvalidArgumentException(
                sprintf('Expected callable, got %s', is_object($filter) ? get_class($filter) : gettype($filter))
            );
        }

        if (null !== $map && !is_callable($map) && !$map instanceof Closure) {
            throw new InvalidArgumentException(
                sprintf('Expected callable, got %s', is_object($map) ? get_class($map) : gettype($map))
            );
        }

        $this->iterable = $iterable;
        $this->filter = $filter;
        $this->map = $map;
    }

    /**
     * @param callable $filter
     * @return self
     */
    public function withFilter($filter)
    {
        return new self($this->iterable, $filter, $this->map);
    }

    /**
     * @param callable $map
     * @return self
     */
    public function withMap($map)
    {
        return new self($this->iterable, $this->filter, $map);
    }

    /**
     * @inheritdoc
     */
    public function getIterator()
    {
        $iterable = $this->iterable;
        if (null !== $this->filter) {
            $iterable = iterable_filter($iterable, $this->filter);
        }
        if (null !== $this->map) {
            $iterable = iterable_map($iterable, $this->map);
        }
        return iterable_to_traversable($iterable);
    }

    /**
     * @return array
     */
    public function asArray()
    {
        $iterable = $this->iterable;
        if (null !== $this->filter) {
            $iterable = iterable_filter($iterable, $this->filter);
        }
        if (null !== $this->map) {
            $iterable = iterable_map($iterable, $this->map);
        }
        return iterable_to_array($iterable);
    }
}
