<?php

if (!function_exists('is_iterable')) {

    /**
     * Check wether or not a variable is iterable (i.e array or \Traversable)
     *
     * @param  array|\Traversable $iterable
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
     * @param  array|\Traversable $iterable
     * @return array
     */
    function iterable_to_array($iterable)
    {
        return is_array($iterable) ? $iterable : iterator_to_array($iterable);
    }
}

if (!function_exists('iterable_to_traversable')) {

    /**
     * If the iterable is not intance of \Traversable, it is an array => convert it to an ArrayIterator.
     *
     * @param  $iterable
     * @return \Traversable
     */
    function iterable_to_traversable($iterable)
    {
        if ($iterable instanceof Traversable) {
            return $iterable;
        }
        elseif (is_array($iterable)) {
            return new ArrayIterator($iterable);
        }
        else {
            throw new \InvalidArgumentException(sprintf('Expected array or \\Traversable, got %s', is_object($iterable) ? get_class($iterable) : gettype($iterable)));
        }
    }
}