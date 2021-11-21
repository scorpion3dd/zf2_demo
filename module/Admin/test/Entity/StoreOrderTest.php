<?php
/**
 * This file is part of the Simple demo web-project with REST Full API for Mobile.
 *
 * This project is no longer maintained.
 * The project is written in Zend Framework 2 Release.
 *
 * @link https://github.com/scorpion3dd
 * @copyright Copyright (c) 2016-2021 Denis Puzik <scorpion3dd@gmail.com>
 */

namespace AdminTest\Entity;

use Admin\Entity\StoreOrder;
use Admin\Entity\StoreProduct;
use MobileTest\AbstractMock;

/**
 * Class StoreOrderTest
 * @package AdminTest\Entity
 */
class StoreOrderTest extends AbstractMock
{
    public function testExchangeArray()
    {
        $id = 11;
        $store_order_id = 101;
        $store_product_id = 201;
        $qty = 3;
        $total = 332;
        $stamp = 'table';
        $first_name = 'Ivan';
        $last_name = 'Ivanov';
        $email = 'ivanov@gmail.com';
        $ship_to_street = 'st. Pushkina';
        $ship_to_city = 'Kiev';
        $ship_to_state = 'state';
        $ship_to_zip = 40001;
        $storeOrder = new StoreOrder();
        $storeOrder->exchangeArray([
            'id' => $id,
            'store_order_id' => $store_order_id,
            'store_product_id' => $store_product_id,
            'qty' => $qty,
            'total' => $total,
            'stamp' => $stamp,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'ship_to_street' => $ship_to_street,
            'ship_to_city' => $ship_to_city,
            'ship_to_state' => $ship_to_state,
            'ship_to_zip' => $ship_to_zip,
        ]);
        $this->assertSame($id, $storeOrder->id);
        $this->assertSame($store_order_id, $storeOrder->store_order_id);
        $this->assertSame($store_product_id, $storeOrder->store_product_id);
        $this->assertSame($qty, $storeOrder->qty);
        $this->assertSame(332.0, $storeOrder->total);
        $this->assertSame('new', $storeOrder->status);
        $this->assertSame($stamp, $storeOrder->stamp);
        $this->assertSame($first_name, $storeOrder->first_name);
        $this->assertSame($last_name, $storeOrder->last_name);
        $this->assertSame($email, $storeOrder->email);
        $this->assertSame($ship_to_street, $storeOrder->ship_to_street);
        $this->assertSame($ship_to_city, $storeOrder->ship_to_city);
        $this->assertSame($ship_to_state, $storeOrder->ship_to_state);
        $this->assertSame($ship_to_zip, $storeOrder->ship_to_zip);
    }

    public function testGetProduct()
    {
        $storeOrder = new StoreOrder();
        $storeProduct = new StoreProduct();
        $storeOrder->setProduct($storeProduct);
        $this->assertSame($storeProduct, $storeOrder->getProduct());
    }

    public function testCalculateSubTotal()
    {
        $storeProduct = new StoreProduct();
        $storeProduct->cost = 105;
        $storeOrder = new StoreOrder();
        $storeOrder->qty = 3;
        $storeOrder->setProduct($storeProduct);
        $storeOrder->calculateSubTotal();
        $this->assertSame((float)($storeOrder->qty * $storeOrder->getProduct()->cost), $storeOrder->total);
    }

    public function testSetQuantity()
    {
        $storeProduct = new StoreProduct();
        $storeProduct->cost = 105;
        $storeOrder = new StoreOrder();
        $storeOrder->setProduct($storeProduct);
        $storeOrder->setQuantity(7);
        $this->assertSame((float)($storeOrder->qty * $storeOrder->getProduct()->cost), $storeOrder->total);
    }
}
