<?php

namespace php_dtos\Json;

/**
 * Represents an object that can be decoded from JSON data.
 */
interface JsonDeserializable {

  /**
   * Creates an instance of self from decoded JSON data.
   *
   * @param array|\stdClass $json
   *   Decoded JSON representation.
   * @param array $context
   *   A key-value array to pass in context for deserialization implementations.
   *
   * @return \self|null
   *   An instance of the implementing class.
   */
  public static function jsonDeserialize($json, array $context = []);

}
