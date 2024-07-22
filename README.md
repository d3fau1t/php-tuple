# Tuple

Tuple is a fluent, high-performance, and memory-efficient immutable array implementation for PHP, leveraging the power of `SplFixedArray` for optimal performance.

## Table of Contents

- [Installation](#installation)
- [Usage](#usage)
- [Performance](#performance)
- [API](#api)
- [Contributing](#contributing)
- [License](#license)

## Installation

You can install the `Tuple` class using Composer. Add the following to your `composer.json` file:

```json
{
    "require": {
        "d3fau1t/tuple": "1.0.0"
    }
}
```

Then run:

```shell
composer install
```

Alternatively, you can require the package directly from the command line:

```shell
composer require d3fau1t/tuple
```

## Usage

### Creating a Tuple

You can create a tuple by using the Tuple class directly or by using the tuple helper function.


```php
use D3fau1t\Tuple\Tuple;

// Using the Tuple class directly
$tuple = new Tuple('hello world', true, 0, null);
```

```php
// Using the tuple helper function
$tuple = tuple('hello world', true, 0, null);
```

### Accessing Tuple Elements

You can access elements in the tuple using array-like syntax.

```php
echo $tuple[0]; // Output: hello world
echo $tuple[1]; // Output: true
```

### Tuple Immutability

Tuples are immutable. You cannot modify, add, or remove elements once the tuple is created.

```php
try {
    $tuple[0] = 'new value';
} catch (LogicException $e) {
    echo $e->getMessage(); // Output: Tuple items cannot be updated, added, or removed
}
```

```php
try {
    unset($tuple[0]);
} catch (LogicException $e) {
    echo $e->getMessage(); // Output: Tuple items cannot be updated, added, or removed
}
```

### Converting Tuple to Array
You can convert a tuple to an array using the toArray method.

```php
$array = $tuple->toArray();
print_r($array); // Output: ['hello world', true, 0, null]
```

### JSON Serialization

Tuples can be serialized to JSON.

```php
$json = json_encode($tuple);
echo $json; // Output: ["hello world",true,0,null]
```

## Performance

The Tuple class is engineered for high performance and memory efficiency. By leveraging SplFixedArray, it provides substantial performance improvements over traditional PHP arrays for fixed-size collections. SplFixedArray minimizes memory overhead and ensures faster access times, making the Tuple class an ideal choice for performance-critical applications. The immutability of tuples guarantees that once created, the data structure remains constant, offering thread safety and predictability in concurrent environments. This design ensures that the Tuple class is both robust and efficient, suitable for a wide range of use cases where performance and memory efficiency are paramount.

## API

`Tuple`

* `__construct(...$items)`: Creates a new tuple.
* `first()`: Returns the first element in the tuple.
* `last()`: Returns the last element in the tuple.
* `keys()`: Returns the keys of the tuple items.
* `get($offset)`: Gets an item at the given offset.
* `isEmpty()`: Determines if the tuple is empty.
* `count()`: Returns the number of items in the tuple.
* `containsOneItem()`: Determines if the tuple contains a single item.
* `search($value, $strict = false)`: Searches the tuple for a given value and returns the corresponding key if successful.
* `before($value, $strict = false)`: Gets the item before the given item.
* `after($value, $strict = false)`: Gets the item after the given item.
* `has($key)`: Determines if an item exists in the tuple by key.
* `hasAny($key)`: Determines if any of the keys exist in the tuple.
* `toArray()`: Converts the tuple to an array.
* `jsonSerialize()`: Converts the tuple to an array for JSON serialization.
* `getIterator()`: Returns an iterator for the items.
* `offsetExists($offset)`: Checks if an item exists at the given offset.
* `offsetGet($offset)`: Gets an item at the given offset.
* `offsetSet($offset, $value)`: Throws a LogicException (tuples are immutable).
* `offsetUnset($offset)`: Throws a LogicException (tuples are immutable).

## Helper Functions

* `tuple(...$items)`: Creates a new Tuple instance.

## Contributing

Contributions are welcome! Please submit a pull request or open an issue to discuss any changes.

## License

This project is licensed under the MIT License. See the LICENSE file for details.