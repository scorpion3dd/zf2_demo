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

namespace Admin\Repository;

use Admin\Entity\StoreOrder;
use Admin\Entity\StoreProduct;
use Exception;
use Zend\Db\TableGateway\TableGateway;

/**
 * Class StoreOrderRepository
 * @package Admin\Repository
 */
class StoreOrderRepository extends AbstractRepository
{
    protected TableGateway $productTableGateway;

    /**
     * StoreOrderRepository constructor.
     * @param TableGateway $orderTableGateway
     * @param TableGateway $productTableGateway
     */
    public function __construct(TableGateway $orderTableGateway, TableGateway $productTableGateway)
    {
        $this->tableGateway = $orderTableGateway;
        $this->productTableGateway = $productTableGateway;
    }

    /**
     * @param StoreOrder $order
     *
     * @return int|null
     * @throws Exception
     */
    public function saveOrder(StoreOrder $order): ?int
    {
        $data = [
            'store_product_id' => $order->store_product_id,
            'qty' => $order->qty,
            'total'  => $order->total,
            'status'  => $order->status,
            'first_name' => $order->first_name,
            'last_name'  => $order->last_name,
            'email'  => $order->email,
            'ship_to_street' => $order->ship_to_street,
            'ship_to_city'  => $order->ship_to_city,
            'ship_to_state'  => $order->ship_to_state,
            'ship_to_zip'  => $order->ship_to_zip
        ];
        $id = (int)$order->id;
        if ($id == 0) {
            $this->getTableGateway()->insert($data);

            return $this->getTableGateway()->lastInsertValue;
        } else {
            $this->getTableGateway()->update($data, ['id' => $id]);
        }

        return null;
    }

    /**
     * @param int $orderId
     *
     * @return StoreOrder
     * @throws Exception
     */
    public function getOrder(int $orderId): StoreOrder
    {
        $rowset = $this->getTableGateway()->select(['id' => $orderId]);
        /** @var StoreOrder $order */
        /** @phpstan-ignore-next-line */
        $order = $rowset->current();
        $productId = $order->store_product_id;
        $prodRowset = $this->getProductTableGateway()->select(['id' => $productId]);
        /** @var StoreProduct $product */
        /** @phpstan-ignore-next-line */
        $product = $prodRowset->current();
        if (! empty($product)) {
            $order->setProduct($product);
        }

        return $order;
    }

    /**
     * @param int $orderId
     */
    public function deleteOrder(int $orderId): void
    {
        $this->getTableGateway()->delete(['id' => $orderId]);
    }

    /**
     * @param int $orderId
     *
     * @return StoreProduct
     * @throws Exception
     */
    public function getProduct(int $orderId): StoreProduct
    {
        $order = $this->getOrder($orderId);
        $productId = $order->store_product_id;
        $rowset = $this->getProductTableGateway()->select(['id' => $productId]);
        /** @var StoreProduct $product */
        /** @phpstan-ignore-next-line */
        $product = $rowset->current();

        return $product;
    }

    /**
     * @return TableGateway
     */
    public function getProductTableGateway(): TableGateway
    {
        return $this->productTableGateway;
    }
}
