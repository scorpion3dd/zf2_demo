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

namespace Admin\Entity;

use Zend\Stdlib\ParametersInterface;

/**
 * Class StoreOrder
 * @package Admin\Entity
 */
class StoreOrder extends AbstractEntity
{
    public int $id;
    public int $store_order_id;
    public int $store_product_id;
    public int $qty;
    public float $total;
    public string $status;
    public string $stamp;
    public string $first_name;
    public string $last_name;
    public string $email;
    public string $ship_to_street;
    public string $ship_to_city;
    public string $ship_to_state;
    public int $ship_to_zip;

    protected StoreProduct $product;

    /**
     * StoreOrder constructor.
     * @param StoreProduct|null $product
     */
    public function __construct(?StoreProduct $product = null)
    {
        $this->exchangeArray([]);
        $this->status = 'new';
        if (! empty($product)) {
            $this->setProduct($product);
        }
    }

    /**
     * @param ParametersInterface<array>|array<array> $data
     */
    public function exchangeArray($data): void
    {
        $this->id = (! empty($data['id'])) ? $data['id'] : 0;
        $this->store_order_id = (! empty($data['store_order_id'])) ? $data['store_order_id'] : 0;
        $this->store_product_id = (! empty($data['store_product_id'])) ? $data['store_product_id'] : 0;
        $this->qty = (! empty($data['qty'])) ? $data['qty'] : 0;
        $this->total = (! empty($data['total'])) ? $data['total'] : 0;
        $this->status = (! empty($data['status'])) ? $data['status'] : 'new';
        $this->stamp = (! empty($data['stamp'])) ? $data['stamp'] : '';
        $this->first_name = (! empty($data['first_name'])) ? $data['first_name'] : '';
        $this->last_name = (! empty($data['last_name'])) ? $data['last_name'] : '';
        $this->email = (! empty($data['email'])) ? $data['email'] : '';
        $this->ship_to_street = (! empty($data['ship_to_street'])) ? $data['ship_to_street'] : '';
        $this->ship_to_city = (! empty($data['ship_to_city'])) ? $data['ship_to_city'] : '';
        $this->ship_to_state = (! empty($data['ship_to_state'])) ? $data['ship_to_state'] : '';
        $this->ship_to_zip = (! empty($data['ship_to_zip'])) ? $data['ship_to_zip'] : 1;
    }

    /**
     * @param StoreProduct $product
     */
    public function setProduct(StoreProduct $product): void
    {
        $this->product = $product;
        $this->store_product_id = $product->id;
    }

    /**
     * @return StoreProduct
     */
    public function getProduct(): StoreProduct
    {
        return $this->product;
    }

    /**
     * @return int
     */
    public function calculateSubTotal(): int
    {
        $this->total = (int)$this->qty * $this->product->cost;

        return $this->total;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity(int $quantity): void
    {
        $this->qty = $quantity;
        if (! empty($this->product)) {
            $this->calculateSubTotal();
        }
    }
}
