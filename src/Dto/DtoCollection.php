<?php

namespace php_dtos\Dto;

/**
 * Representation of a collection of DTO objects.
 */
class DtoCollection implements DtoCollectionInterface
{

  /**
   * Collection of DTO instances.
   *
   * @var \php_dtos\Dto\DtoBase[]
   */
    protected $dtoCollection;

  /**
   * The type of DTO instance stored in the collection.
   *
   * @var string
   */
    protected $elementType;

  /**
   * DtoCollection constructor.
   *
   * @param string $element_type
   *   The type of DTO instance that's stored in the given collection.
   * @param \php_dtos\Dto\DtoBase[] $collection
   *   Optional collection of Dtos.
   */
    public function __construct($element_type, array $collection = [])
    {
        if (!class_exists($element_type)) {
            $msg = get_called_class() . " could not find the provided DTO element type: {$element_type}.";
            throw new \InvalidArgumentException($msg);
        }
        $this->elementType = $element_type;
        $this->dtoCollection = $collection;
    }

  /**
   * {@inheritdoc}
   */
    public function getCollection()
    {
        return $this->dtoCollection;
    }

  /**
   * {@inheritdoc}
   */
    public function append($dto)
    {
        if ($dto instanceof DtoBase) {
            $dto->setInCollection(true);
            array_push($this->dtoCollection, $dto);
        }
    }

  /**
   * {@inheritdoc}
   */
    public function pop()
    {
        $dto = array_pop($this->dtoCollection);
        if ($dto !== null) {
            $dto->setInCollection(false);
        }

        return $dto;
    }

  /**
   * {@inheritdoc}
   */
    public function merge(DtoCollectionInterface $col)
    {
        return new static($this->getCollectionType(), array_merge($this->getCollection(), $col->getCollection()));
    }

  /**
   * {@inheritdoc}
   */
    public function getIterator()
    {
        return new \ArrayIterator($this->dtoCollection);
    }

  /**
   * {@inheritdoc}
   */
    public function count()
    {
        return count($this->dtoCollection);
    }

  /**
   * {@inheritdoc}
   */
    public function jsonSerialize()
    {
        return $this->dtoCollection;
    }

  /**
   * Deserialization method invokable on the collection object itself.
   *
   * This allows the type of DTO element held by the collection to be passed
   * as context into the JsonDeserializable interface's static method.
   *
   * @param array|\stdClass $json
   *   Decoded JSON collection.
   *
   * @return \self
   *   An instance of a DTO collection.
   */
    public function deserialize($json)
    {
        return self::jsonDeserialize($json, ['type' => $this->getCollectionType()]);
    }

  /**
   * {@inheritdoc}
   */
    public static function jsonDeserialize($json, array $context = [])
    {
        if (!isset($context['type']) || !class_exists($context['type'])) {
            $msg = get_called_class() . ' expects the DTO element type to be passed when deserialization occurs.';
            throw new \InvalidArgumentException($msg);
        }
        if (!is_array($json)) {
            return new DtoCollection($context['type']);
        }

        $collection = new static($context['type']);
        foreach ($json as $item) {
            $collection->append(call_user_func_array([$context['type'], 'jsonDeserialize'], [$item]));
        }

        return $collection;
    }

  /**
   * {@inheritdoc}
   */
    public function getCollectionType()
    {
        return $this->elementType;
    }

  /**
   * {@inheritdoc}
   */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->dtoCollection);
    }

  /**
   * {@inheritdoc}
   */
    public function offsetGet($offset)
    {
        if ($this->offsetExists($offset)) {
            return $this->dtoCollection[$offset];
        }

        return null;
    }

  /**
   * {@inheritdoc}
   */
    public function offsetSet($offset, $value)
    {
        $this->dtoCollection[$offset] = $value;
    }

  /**
   * {@inheritdoc}
   */
    public function offsetUnset($offset)
    {
        unset($this->dtoCollection[$offset]);
    }

  /**
   * {@inheritdoc}
   */
    public function __toString()
    {
        if ($this->count() == 0) {
            return "[] ";
        }

        $representation = "[  ";
        foreach ($this->getCollection() as $item) {
            $representation .= "  { ";
            $representation .= (string) $item;
            $representation .= "  }," . PHP_EOL;
        }

        $representation .= "  ]";
        return $representation;
    }
}
