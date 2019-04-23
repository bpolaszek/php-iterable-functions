<?php

use BenTools\IterableFunctions\IterableObject;

if (version_compare(PHP_VERSION, '5.5') >= 0) {
    include_once __DIR__ . '/iterable-map-php55.php';
} else {
    include_once __DIR__ . '/iterable-map-php53.php';
}

if (!function_exists('is_iterable')) {

    /**
     * Check wether or not a variable is iterable (i.e array or \Traversable)
     *
     * @param  mixed $iterable
     * @return bool
     */
    function is_iterable($iterable)
    {
        return is_array($iterable) || $iterable instanceof \Traversable;
    }
}

if (!function_exists('iterable_to_array')) {

    /**
     * Copy the iterable into an array. If the iterable is already an array, return it.
     *
     * @param  iterable|array|\Traversable $iterable
     * @param  bool                        $use_keys [optional] Whether to use the iterator element keys as index.
     * @return array
     */
    function iterable_to_array($iterable, $use_keys = true)
    {
        return is_array($iterable) ? ($use_keys ? $iterable : array_values($iterable)) : iterator_to_array($iterable, $use_keys);
    }
}

if (!function_exists('iterable_to_traversable')) {

    /**
     * If the iterable is not intance of \Traversable, it is an array => convert it to an ArrayIterator.
     *
     * @param  iterable|array|\Traversable $iterable
     * @return \Traversable
     */
    function iterable_to_traversable($iterable)
    {
        if ($iterable instanceof Traversable) {
            return $iterable;
        } elseif (is_array($iterable)) {
            return new ArrayIterator($iterable);
        } else {
            throw new \InvalidArgumentException(
                sprintf(
                    'Expected array or \\Traversable, got %s',
                    is_object($iterable) ? get_class($iterable) : gettype($iterable)
                )
            );
        }
    }
}

if (!function_exists('iterable_to_generator') && version_compare(PHP_VERSION, '5.5') >= 0) {
    /**
     * Creates a new generator from an iterable (useful for being able to use the valid() method for lazily checking if the iterable is empty)
     *
     * @param  iterable|array|\Traversable $iterable
     * @return \Generator
     */
    function iterable_to_generator($iterable)
    {
        if (!is_iterable($iterable)) {
            throw new \InvalidArgumentException(
                sprintf('Expected iterable, got %s', is_object($iterable) ? get_class($iterable) : gettype($iterable))
            );
        }

        foreach ($iterable as $key => $value) {
            yield $key => $value;
        }
    }
}

if (!function_exists('iterable_filter')) {

    /**
     * Filters an iterable.
     *
     * @param iterable|array|\Traversable $iterable
     * @param callable                    $filter
     * @return array|CallbackFilterIterator
     * @throws InvalidArgumentException
     */
    function iterable_filter($iterable, $filter = null)
    {
        if (!is_iterable($iterable)) {
            throw new \InvalidArgumentException(
                sprintf('Expected array or Traversable, got %s', is_object($iterable) ? get_class($iterable) : gettype($iterable))
            );
        }

        // Cannot rely on callable type-hint on PHP 5.3
        if (null !== $filter && !is_callable($filter) && !$filter instanceof Closure) {
            throw new InvalidArgumentException(
                sprintf('Expected callable, got %s', is_object($filter) ? get_class($filter) : gettype($filter))
            );
        }

        if (null === $filter) {
            $filter = function ($value) {
                return (bool) $value;
            };
        }

        if ($iterable instanceof Traversable) {
            if (!class_exists('CallbackFilterIterator')) {
                throw new \RuntimeException('Class CallbackFilterIterator not found. Try using a polyfill, like symfony/polyfill-php54');
            }
            return new CallbackFilterIterator(new IteratorIterator($iterable), $filter);
        }

        return array_filter($iterable, $filter);
    }

}

/**
 * @param iterable|array|\Traversable $iterable
 * @param callable|null               $filter
 * @param callable|null               $map
 * @return Traversable|IterableObject
 * @throws InvalidArgumentException
 */
function iterable($iterable, $filter = null, $map = null)
{
    return new IterableObject($iterable, $filter, $map);
}
