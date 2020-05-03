<?php

namespace php_dtos\tests\Unit\Dto;

use php_dtos\Dto\DtoBase;
use php_dtos\tests\UnitTestCase;

/**
 * @coversDefaultClass \php_dtos\Dto\DtoBase
 */
class DtoBaseTest extends UnitTestCase
{

  /**
   * Test DTO.
   *
   * @var \php_dtos\Dto\DtoBase
   */
    protected $dto;

  /**
   * {@inheritdoc}
   */
    protected function setUp()
    {
        $this->dto = new TestDto();
    }

  /**
   * Tests jsonDeserialize().
   */
    public function testJsonDeserialize()
    {
        $this->expectException(\InvalidArgumentException::class);
        DtoBase::jsonDeserialize([], []);
    }

  /**
   * Data Provider for testJsonSerialize().
   */
    public function providerJsonSerialize()
    {
        return [
        'In collection' => [true, ['value' => null]],
        'Not in collection' => [false, ['value' => null]],
        ];
    }

  /**
   * Tests jsonSerialize().
   *
   * @param bool $in_collection
   *   Whether the dto is in a collection.
   * @param mixed $expected
   *   The expected result.
   *
   * @dataProvider providerJsonSerialize
   */
    public function testJsonSerialize($in_collection, $expected)
    {
        $this->dto->setInCollection($in_collection);
        $this->assertArrayEquals($expected, $this->dto->jsonSerialize());
    }

  /**
   * Tests setInCollection().
   */
    public function testSetInCollection()
    {
        $this->assertFalse(self::getProperty($this->dto, 'inCollection'));
        $this->dto->setInCollection(true);
        $this->assertTrue(self::getProperty($this->dto, 'inCollection'));
    }

  /**
   * Data Provider for testGetPropertyValue().
   */
    public function providerGetPropertyValue()
    {
        return [
        'valid property' => ['value', null],
        'invalid property' => ['invalid', null, \LogicException::class],
        ];
    }

  /**
   * Tests getPropertyValue().
   *
   * @param string $property
   *   Property name.
   * @param mixed $expected
   *   The expected value.
   * @param string|null $exception
   *   The exception name.
   *
   * @dataProvider providerGetPropertyValue
   */
    public function testGetPropertyValue($property, $expected, $exception = null)
    {
        if ($exception) {
            $this->expectException($exception);
        }

        $this->assertEquals($expected, $this->dto->getPropertyValue($property));
    }

    /**
     * Tests that an exception is thrown when attempting a dynamic property set.
     */
    public function testDynamicPropertySetProtection()
    {
        $this->expectException(\LogicException::class);
        $this->dto->dynamicParam = 'foo';
    }

    /**
     * Tests __toString() with a simple DTO.
     */
    public function testToStringSimple()
    {
        $expected = '{"value":"hello world"}';
        $this->dto->setValue('hello world');
        $this->assertEquals($expected, (string) $this->dto);
    }

    /**
     * Tests __toString() with a complex (nested) DTO.
     */
    public function testToStringComplex()
    {
        $expected = '{"value":{"value":"hello world"}}';
        $this->dto->setValue(new TestDto('hello world'));
        $this->assertEquals($expected, (string) $this->dto);
    }
}

/**
 * Test DTO.
 */
class TestDto extends DtoBase
{

    protected $value;

    /**
     * TestDto constructor.
     *
     * @param mixed $value
     *   The value represented by the DTO. Defaults to NULL.
     */
    public function __construct($value = null)
    {
        $this->value = $value;
    }

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
}
