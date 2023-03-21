<?php

declare(strict_types=1);

namespace BenTools\IterableFunctions;

use ArrayIterator;
use EmptyIterator;
use Traversable;

use function array_slice;
use function array_values;
use function iterator_to_array;

/**
 * Maps a callable to an iterable.
 *
 * @param iterable<TKey, TValue> $iterable
 * @param callable(TValue):TResult $mapper
 *
 * @return iterable<TKey, TResult>
 *
 * @template TKey
 * @template TValue
 * @template TResult
 */
function iterable_map(iterable $iterable, callable $mapper): iterable
{
    return iterable($iterable)->map($mapper);
}

/**
 * Merge iterables
 *
 * @param iterable<TKey, TValue> ...$args
 *
 * @return IterableObject<TKey, TValue>
 *
 * @template TKey
 * @template TValue
 */
function iterable_merge(iterable ...$args): iterable
{
    return iterable($args[0] ?? null)->merge(...array_slice($args, 1));
}

/**
 * Copy the iterable into an array.
 *
 * @param iterable<TKey, TValue> $iterable
 * @param bool $preserveKeys [optional] Whether to use the iterator element keys as index.
 *
 * @return array<array-key, TValue>
 *
 * @psalm-return ($preserveKeys is true ? array<TKey, TValue> : array<int, TValue>)
 * @template TKey of array-key
 * @template TValue
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
 * @param iterable<TKey, TValue> $iterable
 *
 * @return Traversable<TKey, TValue>
 *
 * @template TKey
 * @template TValue
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
 * @param (callable(TValue):bool)|null $filter
 *
 * @psalm-param iterable<TKey, TValue> $iterable
 * @psalm-return iterable<TKey, TValue>
 * @template TKey
 * @template TValue
 */
function iterable_filter(iterable $iterable, ?callable $filter = null): iterable
{
    return iterable($iterable)->filter($filter);
}

/**
 * Reduces an iterable.
 *
 * @param iterable<TValue> $iterable
 * @param TResult $initial
 * @param callable(TResult, TValue):TResult $reduce
 *
 * @return TResult
 *
 * @template TValue
 * @template TResult
 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
 */
function iterable_reduce(iterable $iterable, callable $reduce, $initial = null)
{
    foreach ($iterable as $item) {
        $initial = $reduce($initial, $item);
    }

    return $initial;
}

/**
 * Yields iterable values (leaving out keys).
 *
 * @param iterable<TValue> $iterable
 *
 * @return iterable<int, TValue>
 *
 * @template TValue
 */
function iterable_values(iterable $iterable): iterable
{
    return iterable($iterable)->values();
}

/**
 * @param iterable<TKey, TValue>|null $iterable
 *
 * @return IterableObject<TKey, TValue>
 *
 * @template TKey
 * @template TValue
 */
function iterable(?iterable $iterable): IterableObject
{
    return new IterableObject($iterable ?? new EmptyIterator());
}
