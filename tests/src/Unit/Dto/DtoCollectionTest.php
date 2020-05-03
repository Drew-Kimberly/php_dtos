<?php

namespace php_dtos\tests\Unit\Dto;

use php_dtos\Dto\DtoBase;
use php_dtos\Dto\DtoCollection;
use php_dtos\tests\UnitTestCase;

/**
 * @coversDefaultClass \php_dtos\Dto\DtoCollection
 */
class DtoCollectionTest extends UnitTestCase
{

  /**
   * The collection.
   *
   * @var \php_dtos\Dto\DtoCollectionInterface
   */
    protected $collection;

  /**
   * {@inheritdoc}
   */
    protected function setUp()
    {
        $this->collection = new DtoCollection(TestDtoForCollection::class);
    }

    /**
     * Tests that an exception is thrown when a missing class type is passed into __construct().
     */
    public function testConstructorMissingClassType()
    {
        $this->expectException(\InvalidArgumentException::class);
        new DtoCollection('InvalidClass', []);
    }

  /**
   * Tests getCollection().
   */
    public function testGetCollection()
    {
        $this->assertArrayEquals([], $this->collection->getCollection());
    }

  /**
   * Tests append().
   */
    public function testAppend()
    {
        $dto = new TestDtoForCollection();
        $expected = [$dto];
        $this->collection->append($dto);
        $this->assertArrayEquals($expected, $this->collection->getCollection());
    }

  /**
   * Tests pop().
   */
    public function testPop()
    {
        $dto = new TestDtoForCollection();
        $this->collection->append($dto);
        $this->assertEquals($dto, $this->collection->pop());
    }

  /**
   * Tests merge().
   */
    public function testMerge()
    {
        $dto1 = new TestDtoForCollection();
        $dto2 = new TestDtoForCollection();
        $this->collection->append($dto1);
        $other_col = new DtoCollection(TestDtoForCollection::class, [$dto2]);
        $this->assertArrayEquals([$dto1, $dto2], $this->collection->merge($other_col)->getCollection());
    }

  /**
   * Tests getIterator().
   */
    public function testGetIterator()
    {
        $this->assertInstanceOf(\ArrayIterator::class, $this->collection->getIterator());
    }

  /**
   * Tests count().
   */
    public function testCount()
    {
        $this->assertEquals(0, $this->collection->count());
        $dto1 = new TestDtoForCollection();
        $this->collection->append($dto1);
        $this->assertEquals(1, $this->collection->count());
        $this->collection->pop();
        $this->assertEquals(0, $this->collection->count());
    }

  /**
   * Tests jsonSerialize().
   */
    public function testJsonSerialize()
    {
        $this->assertArrayEquals([], $this->collection->jsonSerialize());
        $dto1 = new TestDtoForCollection();
        $this->collection->append($dto1);
        $this->assertArrayEquals([$dto1], $this->collection->jsonSerialize());
    }

  /**
   * Data Provider for testJsonDeserialize().
   */
    public function providerJsonDeserialize()
    {
        $json = [new \stdClass()];
        $dto = new TestDtoForCollection();
        $dto->setInCollection(true);
      // phpcs:disable
      return [
        'no type context' => [$json, [], NULL, \InvalidArgumentException::class],
        'bad JSON' => [new \stdClass(), ['type' => TestDtoForCollection::class], new DtoCollection(TestDtoForCollection::class)],
        'good deserialization' => [$json, ['type' => TestDtoForCollection::class], new DtoCollection(TestDtoForCollection::class, [$dto])],
      ];
      // phpcs:enable
    }

  /**
   * Tests jsonDeserialize().
   *
   * @param mixed $json
   *   The JSON.
   * @param array $context
   *   The context array.
   * @param mixed $expected
   *   The expected result.
   * @param string|null $exception
   *   The expected exception.
   *
   * @dataProvider providerJsonDeserialize
   */
    public function testJsonDeserialize($json, array $context, $expected, $exception = null)
    {
        if (isset($exception)) {
            $this->expectException($exception);
        }

        $this->assertEquals($expected, DtoCollection::jsonDeserialize($json, $context));
    }

    /**
     * Tests deserialize().
     */
    public function testDeserialize()
    {
        $expected = new DtoCollection(TestDtoForCollection::class);
        $this->assertEquals($expected, $this->collection->deserialize([]));
    }

  /**
   * Tests getCollectionType().
   */
    public function testGetCollectionType()
    {
        $this->assertEquals(TestDtoForCollection::class, $this->collection->getCollectionType());
    }

  /**
   * Tests offsetExists().
   */
    public function testOffsetExists()
    {
        $this->assertFalse($this->collection->offsetExists(0));
        $this->collection->append(new TestDtoForCollection());
        $this->assertTrue($this->collection->offsetExists(0));
    }

  /**
   * Tests offsetGet().
   */
    public function testOffsetGet()
    {
        $this->assertNull($this->collection->offsetGet(0));
        $dto = new TestDtoForCollection();
        $this->collection->append($dto);
        $this->assertEquals($dto, $this->collection->offsetGet(0));
    }

  /**
   * Tests offsetSet().
   */
    public function testOffsetSet()
    {
        $dto = new TestDtoForCollection();
        $this->collection->offsetSet(5, $dto);
        $this->assertEquals($dto, $this->collection[5]);
    }

  /**
   * Tests offsetUnset().
   */
    public function testOffsetUnset()
    {
        $dto = new TestDtoForCollection();
        $this->collection->offsetSet(5, $dto);
        $this->collection->offsetUnset(5);
        $this->assertNull($this->collection[5]);
    }

    /**
     * Tests __toString() on an empty collection.
     */
    public function testToStringEmpty()
    {
        $expected = '[]';
        $this->assertEquals($expected, (string) $this->collection);
    }

    /**
     * Tests __toString() when the collection contains DTOs.
     */
    public function testToStringWithItems()
    {
        $expected = '[{"value":null},{"value":null}]';
        $this->collection->append(new TestDtoForCollection());
        $this->collection->append(new TestDtoForCollection());
        $this->assertEquals($expected, (string) $this->collection);
    }
}

/**
 * Test DTO.
 */
class TestDtoForCollection extends DtoBase
{

    protected $value;

  /**
   * Gets the value.
   *
   * @return mixed
   *   The value.
   */
    public function getValue()
    {
        return $this->value;
    }

  /**
   * Sets the value.
   *
   * @param mixed $value
   *   The value.
   */
    public function setValue($value)
    {
        $this->value = $value;
    }

  /**
   * {@inheritdoc}
   */
    public static function jsonDeserialize($json, array $context = [])
    {
        parent::jsonDeserialize($json, $context);
        return new self();
    }
}
