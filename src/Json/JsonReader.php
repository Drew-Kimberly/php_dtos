<?php

namespace php_dtos\Json;

/**
 * Provides functionality for reading JSON.
 */
class JsonReader {

  /**
   * The JSON to read.
   *
   * @var \stdClass
   */
  protected $json;

  /**
   * Constructs a new instance of JsonReader.
   *
   * @param \stdClass|null $json
   *   The JSON object to read.
   */
  public function __construct($json) {
    if (is_null($json)) {
      $json = new \stdClass();
    }

    $this->json = $json;
  }

  /**
   * Safely reads the value of a provided JSON property.
   *
   * @param string $name
   *   The name of the JSON property.
   *
   * @return mixed|null
   *   The value from the JSON object, or NULL if not found.
   */
  public function readProperty($property) {
    if (isset($this->json->{$property})) {
      return $this->json->{$property};
    }

    return NULL;
  }

}
