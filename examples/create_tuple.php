<?php

require 'vendor/autoload.php';

use D3fau1t\Tuple\Tuple;

// Using the Tuple class directly
$tuple1 = new Tuple('hello world', true, 0, null);
print_r($tuple1->toArray());

// Using the tuple helper function
$tuple2 = tuple('hello world', true, 0, null);
print_r($tuple2->toArray());
