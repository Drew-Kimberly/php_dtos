<?php

namespace php_dtos\tests\Unit\Json;

use php_dtos\Json\JsonReader;
use php_dtos\tests\UnitTestCase;

/**
 * @coversDefaultClass \php_dtos\Json\JsonReader
 */
class JsonReaderTest extends UnitTestCase {

  /**
   * Data Provider for testConstruct().
   */
  public function providerTestConstruct() {
    return [
      'good JSON' => [new \stdClass()],
      'null' => [NULL],
    ];
  }

  /**
   * Tests __construct().
   *
   * @param mixed $json
   *   The provided JSON.
   *
   * @covers \php_dtos\Json\JsonReader::__construct()
   *
   * @dataProvider providerTestConstruct
   */
  public function testConstruct($json) {
    $reader = new JsonReader($json);
    $this->assertInstanceOf(JsonReader::class, $reader);
  }

  /**
   * Tests readProperty().
   */
  public function testReadProperty() {
    $json = new \stdClass();
    $json->test = 'value';
    $reader = new JsonReader($json);
    $this->assertEquals('value', $reader->readProperty('test'));
    $this->assertNull($reader->readProperty('nonexistent'));
  }

}
