<?php

namespace Tests;

use Services\ParseOrderService;

(new test)->run();

/**
 * This is a very basic test class that demonstrates
 * reading a json order, parsing it into an Order
 * entity and retrieving the data via the
 * various allowed methods
 */
class test
{
    public function run(): void
    {
        spl_autoload_register([$this, 'autoloader']);

        $json = $this->getOrderJson();
        $parser = new ParseOrderService;
        $order = $parser->parseJsonOrder($json);

        print_r($order->toArray());
        print_r($order->toJson());

        // Accessing LineItems collection
        foreach ($order->lineItems as $lineItem) {
            print_r($lineItem->toArray());
        }
    }

    public function autoloader($class) {
        $class_path = str_replace('\\', '/', $class);
        $file =  __DIR__ . '/../' . $class_path . '.php';
        if (file_exists($file)) {
            require $file;
        }
    }

    private function getOrderJson(): string
    {
        return file_get_contents(__DIR__ . '/test_order.json');
    }
}