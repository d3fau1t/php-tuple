<?php

require 'vendor/autoload.php';

use D3fau1t\Tuple\Tuple;

// Create a tuple
$tuple = new Tuple('hello world', true, 0, null);

// Attempt to modify an element
try {
    $tuple[0] = 'new value';
} catch (LogicException $e) {
    echo $e->getMessage(); // Output: Tuple items cannot be updated, added, or removed
}

// Attempt to unset an element
try {
    unset($tuple[0]);
} catch (LogicException $e) {
    echo $e->getMessage(); // Output: Tuple items cannot be updated, added, or removed
}
