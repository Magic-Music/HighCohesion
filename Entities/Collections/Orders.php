<?php

namespace Entities\Collections;

use App\Entities\EntityCollection;
use Entities\Order;

class Orders extends EntityCollection
{
    /**
     * Defines that this collection can only contain Order entities
     *
     * This class answers Programming question 5:
     *  - it contains an array of OrderEntity classes
     *  - the EntityCollection class has a public 'get()' method to return the list
     *  - The EntityCollection class is also iterable so foreach is directly supported
     */
    protected function getCollectionEntityType(): string
    {
        return Order::class;
    }
}