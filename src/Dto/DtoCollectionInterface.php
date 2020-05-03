<?php

namespace php_dtos\Dto;

/**
 * Defines an interface for a collection of DTO instances.
 */
interface DtoCollectionInterface extends \IteratorAggregate, DtoInterface, \Countable, \ArrayAccess
{

  /**
   * Returns the collection of Dtos as an array.
   *
   * @return \php_dtos\Dto\DtoInterface[]
   *   Returns the array of Dtos.
   */
    public function getCollection();

  /**
   * Adds a DTO object to the collection.
   *
   * @param \php_dtos\Dto\DtoInterface|null $dto
   *   The DTO instance to add.
   */
    public function append($dto);

  /**
   * Retrieves the last DTO instance from the collection.
   *
   * @return \php_dtos\Dto\DtoInterface|null
   *   The DTO object, or NULL if the collection is empty.
   */
    public function pop();

  /**
   * Merges a DTO Collection instance with the given instance.
   *
   * @param \php_dtos\Dto\DtoCollectionInterface $col
   *   The DTO Collection to merge with.
   *
   * @return \php_dtos\Dto\DtoCollectionInterface
   *   The merged DTO Collection.
   */
    public function merge(DtoCollectionInterface $col);

  /**
   * Returns the DTO type that's contained in the collection.
   *
   * @return string
   *   The type of DTO contained in the collection.
   */
    public function getCollectionType();
}
