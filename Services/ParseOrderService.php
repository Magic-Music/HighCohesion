<?php

namespace Services;

use App\Exceptions\InvalidJsonException;
use Entities\Collections\LineItems;
use Entities\LineItem;
use Entities\Order;
use Entities\ShippingAddress;

class ParseOrderService
{
    private Order $order;

    public function __construct()
    {
        $this->order = new Order;
    }

    public function parseJsonOrder(string $jsonOrderData): Order
    {
        $data = json_decode($jsonOrderData, JSON_OBJECT_AS_ARRAY);

        if (is_null($data)) {
            throw new InvalidJsonException("Order data is invalid JSON");
        }

        try {
            $shippingAddress = $this->parseShippingAddress($data['shippingAddress']);
            $lineItems = $this->parseLineItems($data['line_items']);

            $orderData = [
                'orderNumber' => $data['order_number'],
                'title' => $data['title'],
                'currency' => $data['title'],
                'shippingAddress' => $shippingAddress,
                'total' => $data['total'],
                'lineItems' => $lineItems,
            ];

            $this->order->set($orderData);
        } catch (\Exception $e) {
            throw new InvalidJsonException("Order contains invalid data: " . $e->getMessage());
        }

        return $this->order;
    }

    private function parseShippingAddress(array $address): ShippingAddress
    {
        return new ShippingAddress([
            'address1'      => $address['address1'],
            'address2'      => $address['address2'] ?? '',
            'address3'      => $address['address3'] ?? '',
            'town'          => $address['town'] ?? '',
            'city'          => $address['city'] ?? '',
            'countryCode'   => $address['country_code'],
            'zip'           => $address['zip'],
        ]);
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