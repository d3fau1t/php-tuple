<?php

require 'vendor/autoload.php';

use D3fau1t\Tuple\Tuple;

// Create a tuple
$tuple = new Tuple('hello world', true, 0, null);

// Convert the tuple to an array
$array = $tuple->toArray();
print_r($array); // Output: ['hello world', true, 0, null]
