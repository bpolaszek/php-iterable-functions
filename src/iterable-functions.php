<?php

namespace BenTools\IterableFunctions;


use ArrayIterator;
use CallbackFilterIterator;
use IteratorIterator;
use Traversable;

/**
 * Maps a callable to an iterable.
 *
 * @param array|Traversable $iterable
 * @return array|ArrayIterator
 */
function iterable_map(iterable $iterable, callable $map): iterable
{
    if ($iterable instanceof Traversable) {
        return new ArrayIterator(array_map($map, iterator_to_array($iterable)));
    }

    return array_map($map, $iterable);
}


/**
 * Copy the iterable into an array. If the iterable is already an array, return it.
 *
 * @param array|Traversable $iterable
 * @param bool $use_keys [optional] Whether to use the iterator element keys as index.
 * @return array
 */
function iterable_to_array(iterable $iterable, bool $use_keys = true): array
{
    if ($iterable instanceof Traversable) {
        return iterator_to_array($iterable, $use_keys);
    }

    return $use_keys ? $iterable : array_values($iterable);
}

/**
 * If the iterable is not intance of Traversable, it is an array => convert it to an ArrayIterator.
 *
 * @param array|Traversable $iterable
 */
function iterable_to_traversable(iterable $iterable): Traversable
{
    if ($iterable instanceof Traversable) {
        return $iterable;
    }

    return new ArrayIterator($iterable);
}


/**
 * Filters an iterable.
 *
 * @param array|Traversable $iterable
 * @return array|CallbackFilterIterator
 */
function iterable_filter(iterable $iterable, ?callable $filter = null)
{
    if (null === $filter) {
        $filter = static function ($value) {
            return (bool) $value;
        };
    }

    if ($iterable instanceof Traversable) {
        return new CallbackFilterIterator(new IteratorIterator($iterable), $filter);
    }

    return array_filter($iterable, $filter);
}


/**
 * Reduces an iterable.
 *
 * @param iterable<mixed> $iterable
 * @param callable(mixed, mixed) $reduce
 * @return mixed
 *
 * @psalm-template TValue
 * @psalm-template TResult
 *
 * @psalm-param iterable<TValue> $iterable
 * @psalm-param callable(TResult|null, TValue) $reduce
 * @psalm-param TResult|null $initial
 *
 * @psalm-return TResult|null
 */
function iterable_reduce(iterable $iterable, callable $reduce, $initial = null)
{
    foreach ($iterable as $item) {
        $initial = $reduce($initial, $item);
    }

    return $initial;
}

function iterable(iterable $iterable, ?callable $filter = null, ?callable $map = null): IterableObject
{
    return new IterableObject($iterable, $filter, $map);
}
