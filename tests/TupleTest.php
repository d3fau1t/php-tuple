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

    public function testAssociativeArrayThrowsException()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Tuple does not support associative arrays.');

        $tuple = new Tuple(['key' => 'value']);
    }
}
