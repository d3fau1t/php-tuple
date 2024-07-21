<?php

require 'vendor/autoload.php';

use D3fau1t\Tuple\Tuple;

// Define the helper function if it doesn't exist
if (!function_exists('tuple')) {
    function tuple(...$items): Tuple
    {
        return new Tuple(...$items);
    }
}

// Create a tuple using the helper function
$tuple = tuple('hello world', true, 0, null);
print_r($tuple->toArray()); // Output: ['hello world', true, 0, null]
