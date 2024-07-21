<?php

require 'vendor/autoload.php';

use D3fau1t\Tuple\Tuple;

// Create a tuple
$tuple = new Tuple('hello world', true, 0, null);

// Serialize the tuple to JSON
$json = json_encode($tuple);
echo $json; // Output: ["hello world",true,0,null]
