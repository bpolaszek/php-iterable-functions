<?php

declare(strict_types=1);

namespace BenTools\IterableFunctions;

use IteratorAggregate;
use Traversable;

/**
 * @internal
 *
 * @implements IteratorAggregate<mixed>
 */
final class MappedTraversable implements IteratorAggregate
{
    /** @var Traversable<mixed> */
    private $traversable;

    /** @var callable */
    private $mapper;

    /**
     * @param Traversable<mixed> $traversable
     */
    public function __construct(Traversable $traversable, callable $mapper)
    {
        $this->traversable = $traversable;
        $this->mapper = $mapper;
    }

    /**
     * @return Traversable<mixed>
     */
    public function getIterator(): Traversable
    {
        foreach ($this->traversable as $key => $value) {
            yield $key => ($this->mapper)($value);
        }
    }
}
