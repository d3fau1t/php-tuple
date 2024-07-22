<?php

namespace D3fau1t\Tuple;

use ArrayAccess;
use ArrayIterator;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;
use JsonSerializable;
use LogicException;
use SplFixedArray;
use Stringable;
use Traversable;

/**
 * @template TKey of int
 * @template-covariant TValue
 * @implements ArrayAccess<TKey, TValue>
 * @implements IteratorAggregate<TKey, TValue>
 */
class Tuple implements ArrayAccess, Countable, JsonSerializable, Stringable, IteratorAggregate
{
    /**
     * The items contained in the tuple.
     *
     * @var SplFixedArray<TValue>
     */
    protected SplFixedArray $items;

    /**
     * The size of the tuple.
     *
     * @var int
     */
    protected int $size = 0;

    /**
     * Create a new tuple.
     *
     * @param TValue ...$items
     * @return void
     * @throws InvalidArgumentException
     */
    public function __construct(...$items)
    {
        if (count($items) === 0) {
            $this->items = new SplFixedArray(0);
        } else {
            $items = (count($items) === 1 && is_array($items[0])) ? $items[0] : $items;

            if (!array_is_list($items)) {
                throw new InvalidArgumentException('Tuple does not support associative arrays.');
            }

            $this->size = count($items);
            $this->items = SplFixedArray::fromArray($this->freeze($items));
        }
    }

    /**
     * Recursively makes arrays immutable.
     *
     * @param array<int, mixed> $items
     * @return array<int, mixed>
     */
    private function freeze(array $items): array
    {
        foreach ($items as $key => $item) {
            if (is_array($item)) {
                $items[$key] = new self($item);
            }
        }

        return $items;
    }

    /**
     * Get the keys of the tuple items.
     *
     * @return array<int>
     */
    public function keys(): array
    {
        return array_keys($this->items->toArray());
    }

    /**
     * Get an item at a given offset.
     *
     * @param int $offset
     * @return TValue
     */
    public function get($offset): mixed
    {
        return $this->offsetGet($offset);
    }

    /**
     * Get the first element in the tuple.
     *
     * @return TValue|null
     */
    public function first(): mixed
    {
        return $this->offsetGet(0);
    }

    /**
     * Get the last element in the tuple.
     *
     * @return TValue|null
     */
    public function last(): mixed
    {
        return $this->offsetGet($this->size - 1);
    }

    /**
     * Count the number of items in the tuple.
     *
     * @return int
     */
    public function count(): int
    {
        return $this->size;
    }

    /**
     * Determine if the tuple is empty or not.
     *
     * @phpstan-assert-if-true null $this->first()
     *
     * @phpstan-assert-if-false TValue $this->first()
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->size === 0;
    }

    /**
     * Determine if the tuple contains a single item.
     *
     * @return bool
     */
    public function containsOneItem(): bool
    {
        return $this->count() === 1;
    }

    /**
     * Search the tuple for a given value and return the corresponding key if successful.
     *
     * @param TValue|(callable(TValue,TKey): bool) $value
     * @param bool $strict
     * @return TKey|false
     */
    public function search(mixed $value, bool $strict = true): mixed
    {
        if (!$this->isCallable($value)) {
            foreach ($this->items as $key => $item) {
                if ($strict ? $item === $value : $item == $value) {
                    return $key;
                }
            }
            return false;
        }

        foreach ($this->items as $key => $item) {
            if ($value($item, $key)) {
                return $key;
            }
        }

        return false;
    }

    /**
     * Get the item before the given item.
     *
     * @param TValue|(callable(TValue,TKey): bool) $value
     * @param bool $strict
     * @return TValue|null
     */
    public function before(mixed $value, bool $strict = true): mixed
    {
        $key = $this->search($value, $strict);
        if ($key === false || $key <= 0) {
            return null;
        }

        return $this->get($key - 1);
    }

    /**
     * Get the item after the given item.
     *
     * @param TValue|(callable(TValue,TKey): bool) $value
     * @param bool $strict
     * @return TValue|null
     */
    public function after(mixed $value, bool $strict = true): mixed
    {
        $key = $this->search($value, $strict);
        if ($key === false || $key >= $this->size - 1) {
            return null;
        }

        return $this->get($key + 1);
    }

    /**
     * Determine if an item exists in the tuple by key.
     *
     * @param TKey|array<int, TKey> $key
     * @return bool
     */
    public function has(int|array $key): bool
    {
        if (is_array($key)) {
            foreach ($key as $k) {
                if (!$this->offsetExists($k)) {
                    return false;
                }
            }
            return true;
        }

        return $this->offsetExists($key);
    }

    /**
     * Determine if any of the keys exist in the tuple.
     *
     * @param TKey|array<int, TKey> $key
     * @return bool
     */
    public function hasAny(int|array $key): bool
    {
        if (is_array($key)) {
            foreach ($key as $k) {
                if ($this->offsetExists($k)) {
                    return true;
                }
            }
            return false;
        }

        return $this->offsetExists($key);
    }

    /**
     * Determine if an item exists at an offset.
     *
     * @param int $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return isset($this->items[$offset]);
    }

    /**
     * Get an item at a given offset.
     *
     * @param int $offset
     * @return TValue
     */
    public function offsetGet($offset): mixed
    {
        return $this->items[$offset];
    }

    /**
     * Set the item at a given offset.
     *
     * @param int $offset
     * @param TValue $value
     * @return void
     * @throws LogicException
     */
    public function offsetSet($offset, mixed $value): void
    {
        throw new LogicException('Tuple items cannot be updated, added, or removed.');
    }

    /**
     * Unset the item at a given offset.
     *
     * @param int $offset
     * @return void
     * @throws LogicException
     */
    public function offsetUnset($offset): void
    {
        throw new LogicException('Tuple items cannot be updated, added, or removed.');
    }

    /**
     * Get an iterator for the items.
     *
     * @return ArrayIterator<int, TValue>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }

    /**
     * Convert the tuple to an array for JSON serialization.
     *
     * @return array<int, mixed>
     */
    public function jsonSerialize(): array
    {
        return array_map(function ($value) {
            return $value instanceof self ? $value->jsonSerialize() : $value;
        }, (array)$this->items);
    }

    /**
     * Convert the tuple to an array.
     *
     * @return array<int, mixed>
     */
    public function toArray(): array
    {
        return $this->jsonSerialize();
    }

    /**
     * Convert the tuple to a JSON string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return json_encode($this->jsonSerialize());
    }

    /**
     * Determine if the given value is callable, but not a string.
     *
     * @param mixed $value
     * @return bool
     */
    protected function isCallable(mixed $value): bool
    {
        return !is_string($value) && is_callable($value);
    }
}
