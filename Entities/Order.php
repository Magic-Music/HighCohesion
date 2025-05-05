<?php

namespace Entities;

use Entities\Collections\LineItems;

/**
 * This class answers Programming question 1
 */
class Order extends \App\Entities\Entity
{
    public string $orderNumber;
    public string $currency = "GBP"; //Default values can be set on entity properties
    public ShippingAddress $shippingAddress; //Properties could also be other entities
    public int $total;
    public LineItems $lineItems; // Entity collection allows an array of a specific entity type
}