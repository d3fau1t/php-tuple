<?php

use D3fau1t\Tuple\Tuple;

/**
 * Creates a new Tuple instance.
 *
 * @template TKey of int
 * @template-covariant TValue
 *
 * @param TValue ...$items
 * @return Tuple<TKey, TValue>
 */
if (!function_exists('tuple')) {
    function tuple(...$items): Tuple
    {
        return new Tuple(...$items);
    }
}