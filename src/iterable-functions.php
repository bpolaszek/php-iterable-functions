<?php

declare(strict_types=1);

namespace BenTools\IterableFunctions;

use ArrayIterator;
use CallbackFilterIterator;
use IteratorIterator;
use Traversable;

use function array_filter;
use function array_values;
use function iterator_to_array;

/**
 * Maps a callable to an iterable.
 *
 * @param iterable<mixed> $iterable
 *
 * @return iterable<mixed>
 */
function iterable_map(iterable $iterable, callable $map): iterable
{
    foreach ($iterable as $key => $item) {
        yield $key => $map($item);
    }
}

/**
 * Copy the iterable into an array. If the iterable is already an array, return it.
 *
 * @param iterable<mixed> $iterable
 * @param bool $preserveKeys [optional] Whether to use the iterator element keys as index.
 *
 * @return array<mixed>
 */
function iterable_to_array(iterable $iterable, bool $preserveKeys = true): array
{
    if ($iterable instanceof Traversable) {
        return iterator_to_array($iterable, $preserveKeys);
    }

    return $preserveKeys ? $iterable : array_values($iterable);
}

/**
 * If the iterable is not instance of Traversable, it is an array => convert it to an ArrayIterator.
 *
 * @param iterable<mixed> $iterable
 *
 * @return Traversable<mixed>
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
 * @param iterable<mixed> $iterable
 *
 * @return array<mixed>|CallbackFilterIterator
 */
function iterable_filter(iterable $iterable, ?callable $filter = null)
{
    if ($filter === null) {
        $filter =
            /** @param mixed $value */
            static function ($value): bool {
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
 * @param callable(mixed, mixed):mixed $reduce
 *
 * @return mixed
 *
 * @psalm-template TValue
 * @template TResult
 * @psalm-param iterable<TValue> $iterable
 * @psalm-param callable(TResult|null, TValue):TResult $reduce
 * @psalm-param TResult|null $initial
 * @psalm-return TResult|null
 */
function iterable_reduce(iterable $iterable, callable $reduce, $initial = null)
{
    foreach ($iterable as $item) {
        $initial = $reduce($initial, $item);
    }

    return $initial;
}

/**
 * @param iterable<mixed> $iterable
 */
function iterable(iterable $iterable): IterableObject
{
    return IterableObject::new($iterable);
}
