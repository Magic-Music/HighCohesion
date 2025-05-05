<?php

namespace App\Entities;

use App\Exceptions\InvalidEntityValueException;
use Countable;
use Iterator;

abstract class EntityCollection implements Iterator, Countable
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

    // The following methods implement the Iterator/Countable interfaces
    // to allow the collection to be used in foreach loops
    // TODO - Add ArrayAccess interface and methods

    public function get(): array
    {
        return $this->collectionItems;
    }

    public function current(): mixed
    {
        return $this->collectionItems[$this->pointer];
    }

    public function key(): mixed
    {
        return $this->pointer;
    }

    public function next(): void
    {
        $this->pointer++;
    }

    public function rewind(): void
    {
        $this->pointer = 0;
    }

    public function valid(): bool
    {
        return $this->pointer < count($this->collectionItems);
    }

    public function count(): int
    {
        return count($this->collectionItems);
    }
}