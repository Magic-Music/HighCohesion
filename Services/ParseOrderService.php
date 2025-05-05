<?php

namespace Services;

use App\Exceptions\InvalidJsonException;
use Entities\Collections\LineItems;
use Entities\LineItem;
use Entities\OrderEntity;
use Entities\ShippingAddressEntity;

class ParseOrderService
{
    private OrderEntity $order;

    public function __construct(string $jsonOrderData)
    {
        $this->parseJsonOrder($jsonOrderData);

        return $this->order;
    }

    private function parseJsonOrder($jsonOrderData): void
    {
        $data = json_decode($jsonOrderData, JSON_OBJECT_AS_ARRAY);

        if (is_null($data)) {
            throw new InvalidJsonException("Order data is invalid JSON");
        }

        try {
            // This updates shippingAddress and line_items
            // to be an entity and entity collection
            // If immutable variables are preferred, the individual
            // items can be passed into the order->set method
            $data['shippingAddress'] = new ShippingAddressEntity($data['shippingAddress']);
            $data['line_items'] = $this->parseLineItems($data['line_items']);

            $this->order->set($data);
        } catch (\Exception $e) {
            throw new InvalidJsonException("Order contains invalid data: " . $e->getMessage());
        }
    }

    private function parseLineItems($items): LineItems
    {
        $lineItems = new LineItems;

        foreach ($items as $item) {
            $lineItems->push(new LineItem($item));
        }

        return $lineItems;
    }

}