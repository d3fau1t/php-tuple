<?php

require 'vendor/autoload.php';

use D3fau1t\Tuple\Tuple;

// Create a tuple
$tuple = new Tuple('hello world', true, 0, null);

// Access elements using array-like syntax
echo $tuple[0]; // Output: hello world
echo $tuple[1]; // Output: 1
