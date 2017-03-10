<?php

if (!function_exists('is_iterable')) {

    /**
     * Check wether or not a variable is iterable (i.e array or \Traversable)
     * @param array|\Traversable $iterable
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
     * @param array|\Traversable $iterable
     * @return array
     */
    function iterable_to_array($iterable)
    {
        return is_array($iterable) ? $iterable : iterator_to_array($iterable);
    }
}
