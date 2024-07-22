<?php

namespace D3fau1t\Tuple\Tests;

use D3fau1t\Tuple\Tuple;
use InvalidArgumentException;
use LogicException;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class TupleTest extends TestCase
{
    public function testTupleImplementsArrayAccessCountableJsonSerializableAndStringable()
    {
        $tuple = new Tuple();
        $this->assertInstanceOf(\ArrayAccess::class, $tuple);
        $this->assertInstanceOf(\Countable::class, $tuple);
        $this->assertInstanceOf(\JsonSerializable::class, $tuple);
        $this->assertInstanceOf(\Stringable::class, $tuple);
        $this->assertInstanceOf(\IteratorAggregate::class, $tuple);
    }

    public function testTupleCreationWithSpreadItems()
    {
        $tuple = new Tuple(1, 'a', true);
        $this->assertEquals(1, $tuple->offsetGet(0));
        $this->assertEquals('a', $tuple->offsetGet(1));
        $this->assertEquals(true, $tuple->offsetGet(2));
    }

    public function testTupleCreationWithArray()
    {
        $tuple = new Tuple([1, 'a', true]);
        $this->assertEquals(1, $tuple->offsetGet(0));
        $this->assertEquals('a', $tuple->offsetGet(1));
        $this->assertEquals(true, $tuple->offsetGet(2));
    }

    public function testTupleCountWithSpreadItems()
    {
        $tuple = new Tuple(1, 'a', true);
        $this->assertCount(3, $tuple);
    }

    public function testTupleCountWithArray()
    {
        $tuple = new Tuple([1, 'a', true]);
        $this->assertCount(3, $tuple);
    }

    public function testTupleIsImmutableOnSet()
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Tuple items cannot be updated, added, or removed.');

        $tuple = new Tuple('hello');
        $tuple[0] = 'world';
    }

    public function testTupleIsImmutableOnUnset()
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Tuple items cannot be updated, added, or removed.');

        $tuple = new Tuple('world');
        unset($tuple[0]);
    }

    public function testInvalidOffsetAccess()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Index invalid or out of range');

        $tuple = new Tuple('a', 'b');
        $tuple->offsetGet(3);
    }

    public function testMultiDimensionalArray()
    {
        $tuple = new Tuple([1, [2, 3], 'a']);
        $this->assertInstanceOf(Tuple::class, $tuple->offsetGet(1));
        $this->assertEquals(2, $tuple->offsetGet(1)->offsetGet(0));
        $this->assertEquals(3, $tuple->offsetGet(1)->offsetGet(1));
    }

    public function testEmptyTuple()
    {
        $tuple = new Tuple();
        $this->assertCount(0, $tuple);
    }

    public function testTupleWithNullValues()
    {
        $tuple = new Tuple(null, null);
        $this->assertNull($tuple->offsetGet(0));
        $this->assertNull($tuple->offsetGet(1));
    }

    public function testTupleWithMixedTypes()
    {
        $tuple = new Tuple(1, 'string', 3.14, true, null);
        $this->assertEquals(1, $tuple->offsetGet(0));
        $this->assertEquals('string', $tuple->offsetGet(1));
        $this->assertEquals(3.14, $tuple->offsetGet(2));
        $this->assertTrue($tuple->offsetGet(3));
        $this->assertNull($tuple->offsetGet(4));
    }

    public function testToArray()
    {
        $tuple = new Tuple(1, 'a', true);
        $this->assertEquals([1, 'a', true], $tuple->toArray());
    }

    public function testKeys()
    {
        $tuple = new Tuple(1, 'a', true);
        $this->assertEquals([0, 1, 2], $tuple->keys());
    }

    public function testGetElement()
    {
        $tuple = new Tuple(1, 'a', true);
        $this->assertEquals(1, $tuple->get(0));
        $this->assertEquals('a', $tuple->get(1));
        $this->assertEquals(true, $tuple->get(2));
    }

    public function testFirstElement()
    {
        $tuple = new Tuple(1, 'a', true);
        $this->assertEquals(1, $tuple->first());
    }

    public function testLastElement()
    {
        $tuple = new Tuple(1, 'a', true);
        $this->assertEquals(true, $tuple->last());
    }

    public function testIsEmpty()
    {
        $emptyTuple = new Tuple();
        $this->assertTrue($emptyTuple->isEmpty());

        $nonEmptyTuple = new Tuple(1, 'a', true);
        $this->assertFalse($nonEmptyTuple->isEmpty());
    }

    public function testContainsOneItem()
    {
        $singleItemTuple = new Tuple('single');
        $this->assertTrue($singleItemTuple->containsOneItem());

        $multipleItemsTuple = new Tuple(1, 'a', true);
        $this->assertFalse($multipleItemsTuple->containsOneItem());

        $emptyTuple = new Tuple();
        $this->assertFalse($emptyTuple->containsOneItem());
    }

    public function testSearch()
    {
        $tuple = new Tuple(1, 'a', true);
        $this->assertEquals(0, $tuple->search(1));
        $this->assertEquals(1, $tuple->search('a'));
        $this->assertEquals(0, $tuple->search(true, false));
        $this->assertEquals(2, $tuple->search(true));
        $this->assertEquals(2, $tuple->search('not found', false));
        $this->assertFalse($tuple->search('not found'));

        $callback = function ($item, $key) {
            return $item === 'a' && $key === 1;
        };
        $this->assertEquals(1, $tuple->search($callback));
    }

    public function testBefore()
    {
        $tuple = new Tuple(1, 'a', true);
        $this->assertEquals(1, $tuple->before('a'));
        $this->assertEquals('a', $tuple->before(true));
        $this->assertNull($tuple->before(1));
        $this->assertNull($tuple->before('not found'));
    }

    public function testAfter()
    {
        $tuple = new Tuple(1, 'a', true);
        $this->assertEquals('a', $tuple->after(1));
        $this->assertEquals(true, $tuple->after('a'));
        $this->assertNull($tuple->after(true));
        $this->assertNull($tuple->after('not found'));
    }

    public function testHas()
    {
        $tuple = new Tuple(1, 'a', true);
        $this->assertTrue($tuple->has(0));
        $this->assertTrue($tuple->has([0, 1]));
        $this->assertFalse($tuple->has(3));
        $this->assertFalse($tuple->has([0, 3]));
    }

    public function testHasAny()
    {
        $tuple = new Tuple(1, 'a', true);
        $this->assertTrue($tuple->hasAny(0));
        $this->assertTrue($tuple->hasAny([0, 3]));
        $this->assertFalse($tuple->hasAny(3));
        $this->assertFalse($tuple->hasAny([3, 4]));
    }

    public function testAssociativeArrayThrowsException()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Tuple does not support associative arrays.');

        $tuple = new Tuple(['key' => 'value']);
    }
}
