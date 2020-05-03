<?php

namespace php_dtos\Dto;

use php_dtos\Json\JsonDeserializable;

/**
 * Defines a common interface for all DTOs.
 */
interface DtoInterface extends \JsonSerializable, JsonDeserializable
{

  /**
   * Represents the DTO object as a string for diagnostics.
   *
   * @return string
   *   The string representation of the object.
   */
    public function __toString();
}
