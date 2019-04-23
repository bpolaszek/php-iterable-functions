<?php

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
