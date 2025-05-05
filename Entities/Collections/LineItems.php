<?php

namespace Entities\Collections;

use App\Entities\EntityCollection;
use Entities\LineItem;

class LineItems extends EntityCollection
{
    /**
     * Defines that this collection can only contain LineItem entities
     */
    protected function getCollectionEntityType(): string
    {
        return LineItem::class;
    }
}