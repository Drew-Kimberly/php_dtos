<?php

namespace php_dtos\Dto;

/**
 * Abstract representation of a JSON serializable/deserializable DTO object.
 */
abstract class DtoBase implements DtoInterface
{

  /**
   * Whether the DTO is contained within a collection.
   *
   * @var bool
   */
    protected $inCollection = false;

  /**
   * {@inheritdoc}
   */
    public static function jsonDeserialize($json, array $context = [])
    {
        if (is_array($json)) {
            $msg = get_called_class() . ' does not support ::jsonDeserialize() using JSON data decoded as an '
                . 'associative array. Please use JSON data decoded as an object.';
            throw new \InvalidArgumentException($msg);
        }
    }

  /**
   * {@inheritdoc}
   */
    public function jsonSerialize()
    {
        if ($this->inCollection) {
            return $this->toCollectionJson();
        }

        return $this->toFullJson();
    }

  /**
   * Returns the complete JSON representation of the DTO.
   *
   * @return mixed
   *   The complete JSON representation.
   */
    protected function toFullJson()
    {
        $json = [];
        foreach (get_object_vars($this) as $property => $value) {
            if ($property !== 'inCollection') {
                $json[$property] = $value;
            }
        }

        return $json;
    }

  /**
   * Returns the collection JSON representation of the DTO.
   *
   * @return mixed
   *   The collection JSON representation.
   */
    protected function toCollectionJson()
    {
        return $this->toFullJson();
    }

  /**
   * Sets whether the DTO is contained within a DTO collection.
   *
   * @param bool $in_collection
   *   Whether the DTO is contained within a DTO collection.
   */
    public function setInCollection($in_collection)
    {
        $this->inCollection = $in_collection;
    }

  /**
   * Returns the value of a DTO property.
   *
   * @param string $name
   *   The name of the property.
   *
   * @return mixed
   *   The value of the property.
   */
    public function getPropertyValue($name)
    {
        if (property_exists($this, $name)) {
            return $this->{$name};
        }

        throw new \LogicException(get_called_class() . " does not contain the property: {$name}");
    }

  /**
   * Implements the PHP Magic Method __set().
   *
   * Prohibits the dynamic addition of undefined properties on DTO objects.
   *
   * @param string $name
   *   Name of the property.
   * @param mixed $value
   *   Value of the property.
   */
    public function __set($name, $value)
    {
        if (!property_exists($this, $name)) {
            throw new \LogicException(get_called_class() . " does not contain the property: {$name}");
        }
    }

  /**
   * {@inheritdoc}
   */
    public function __toString()
    {
        $representation = '';
        $properties = get_object_vars($this);
        $total = count($properties);
        $count = 0;
        foreach ($properties as $property => $value) {
            $count++;
            if ($property !== 'inCollection') {
                if (!isset($value)) {
                    $value = 'null';
                }
                if ($value instanceof DtoInterface) {
                    $representation .= "{$property}: {$value}";
                } else {
                    $representation .= "{$property}: {$value}";
                    if ($count < $total) {
                        $representation .= "  ||  ";
                    }
                }
            }
        }

        return $representation;
    }
}
