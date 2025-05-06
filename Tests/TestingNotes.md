# Test Plan

This document outlines some potential  **pseudo-code unit test scenarios**. 
Although this project is not a full application and lacks full test tooling, these examples 
demonstrate how we could verify correctness and resilience in a production environment using
PHPUnit or a similar framework.
---

## ParseOrderService Tests

### 1. Valid JSON should produce an OrderEntity

```php
// Given: Valid JSON containing order data
$json = '{ valid JSON string }';

// When: Parsed with ParseOrderService
$order = (new ParseOrderService)->parseJsonOrder($json);

// Then: The result is a populated OrderEntity
assertTrue($order instanceof OrderEntity);
assertEqual("#1001", $order->order_number);
assertCount(2, count($order->line_items));

// Plus further assertions on the data such as the shipping address
```

### 2. Invalid JSON should throw an exception

```php
// Given: A broken JSON string
$json = '{ this is not valid JSON }';

// When: Parsed
// Then: It should throw an InvalidJsonException
expectException(InvalidJsonException);
$order = (new ParseOrderService)->parseJsonOrder($json);
```

### 3. Missing required fields should raise an exception

```php
// Given: JSON missing shippingAddress field
$json = '{ "order_number": "#1001", ... }';

// When: Parsed
// Then: A custom OrderValidationException is thrown
expectException(InvalidJsonException);
$order = (new ParseOrderService)->parseJsonOrder($json);
```

## Orders Collection Tests

### 4. Can add and iterate over OrderEntities

```php
// Given: A new Orders entity collection
$orders = new Orders();

// And: Two valid OrderEntity objects
$order1 = new OrderEntity(order_number: "#1", ..., shippingAddress: ShippingAddressEntity(...), line_items: [...]);
$order2 = new OrderEntity(order_number: "#2", ..., shippingAddress: ShippingAddressEntity(...), line_items: [...]);

// When: Added to the collection
$orders->push($order1);
$orders->push($order2);

// Then: They should be iterable/accessible
assert count($orders) == 2;
assert $orders->get()[1]->order_number == "#1";
```

### 5. Adding non-OrderEntity should fail

```php
// Given: A collection
$orders = new Orders();

// When: Attempting to add a stdClass object
$fakeOrder = new stdClass();

// Then: Expect a type error or custom exception
expectException(InvalidEntityValueException);
$orders->push($fakeOrder);
```
