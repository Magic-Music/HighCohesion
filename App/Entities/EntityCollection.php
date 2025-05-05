<?php

namespace App\Entities;

use App\Exceptions\InvalidEntityValueException;
use Iterator;

abstract class EntityCollection implements Iterator
{
    private array $collectionItems = [];
    private int $pointer = 0;
    private string $collectionEntityType;

    /**
     * Return the class name of the allowed entity
     */
    protected abstract function getCollectionEntityType(): string;

    public function __construct(array $entities = [])
    {
        $this->collectionEntityType = $this->getCollectionEntityType();

        foreach ($entities as $entity) {
            $this->push($entity);
        }
    }

    public function push(Entity $entity): void
    {
        if (!($entity instanceof $this->collectionEntityType)) {
            $entityName =  (new \ReflectionClass($this))->getShortName();
            throw new InvalidEntityValueException(
                "$entityName collection only accepts Entities of type {$this->collectionEntityType}"
            );
        }

        $this->collectionItems[] = $entity;
    }

    // The following methods implement the Iterator interface
    // to allow the collection to be used in foreach loops

    public function get(): array
    {
        return $this->collectionItems;
    }

    public function current() {
        return $this->collectionItems[$this->pointer];
    }

    public function key() {
        return $this->pointer;
    }

    public function next() {
        $this->pointer++;
    }

    public function rewind() {
        $this->pointer = 0;
    }

    public function valid() {
        return $this->pointer < count($this->collectionItems);
    }
}