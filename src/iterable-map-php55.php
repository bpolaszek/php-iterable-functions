<?php

if (!function_exists('iterable_map')) {

    /**
     * Maps a callable to an iterable.
     *
     * @param iterable|array|\Traversable 	$iterable
     * @param callable 						$map
     * @return array|Traversable
     * @throws InvalidArgumentException
     */
    function iterable_map($iterable, callable $map)
    {
        if (!is_iterable($iterable)) {
            throw new \InvalidArgumentException(
                sprintf('Expected array or Traversable, got %s', is_object($iterable) ? get_class($iterable) : gettype($iterable))
            );
        }

        if ($iterable instanceof Traversable) {
            $generator = function ($iterable, $map) {
                foreach ($iterable as $key => $value) {
                    yield $key => $map($value);
                }
            };

            return $generator($iterable, $map);
        }

        return array_map($map, $iterable);
    }

}
