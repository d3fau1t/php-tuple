<?php

require 'vendor/autoload.php';

use D3fau1t\Tuple\Tuple;

// Create a tuple
$tuple = new Tuple('first', 'middle', 'last');

// Get the first element
echo $tuple->first(); // Output: first

// Get the last element
echo $tuple->last(); // Output: last
