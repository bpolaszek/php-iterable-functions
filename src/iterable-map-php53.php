<?php

if (!function_exists('iterable_map')) {

    /**
     * Maps a callable to an iterable.
     *
     * @param iterable|array|\Traversable 	$iterable
     * @param callable 						$map
     * @return array|ArrayIterator
     * @throws InvalidArgumentException
     */
    function iterable_map($iterable, $map)
    {
        if (!is_iterable($iterable)) {
            throw new \InvalidArgumentException(
                sprintf('Expected array or Traversable, got %s', is_object($iterable) ? get_class($iterable) : gettype($iterable))
            );
        }

        // Cannot rely on callable type-hint on PHP 5.3
        if (null !== $map && !is_callable($map) && !$map instanceof Closure) {
            throw new InvalidArgumentException(
                sprintf('Expected callable, got %s', is_object($map) ? get_class($map) : gettype($map))
            );
        }

        if ($iterable instanceof Traversable) {
            return new ArrayIterator(array_map($map, iterator_to_array($iterable)));
        }

        return array_map($map, $iterable);
    }

}
