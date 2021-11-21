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

namespace AdminTest\Repository;

use Admin\Entity\StoreOrder;
use Admin\Entity\StoreProduct;
use Admin\Repository\StoreOrderRepository;
use Exception;
use MobileTest\AbstractMock;

/**
 * Class StoreOrderRepositoryTest
 * @package AdminTest\Repository
 */
class StoreOrderRepositoryTest extends AbstractMock
{
    private StoreOrderRepository $storeOrdersRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->storeOrdersRepository = $this->serviceManager->get('StoreOrdersTable');
    }

    /**
     * @throws Exception
     */
    public function testSaveOrder()
    {
        $storeOrder = new StoreOrder();
        $storeOrder->exchangeArray([
            'id' => 11,
            'store_order_id' => 101,
            'store_product_id' => 201,
            'qty' => 3,
            'total' => 332,
            'stamp' => 'table',
            'first_name' => 'Ivan',
            'last_name' => 'Ivanov',
            'email' => 'ivanov@gmail.com',
            'ship_to_street' => 'st. Pushkina',
            'ship_to_city' => 'Kiev',
            'ship_to_state' => 'state',
            'ship_to_zip' => 40001,
        ]);
        $res = $this->storeOrdersRepository->saveOrder($storeOrder);
        $this->assertEquals(null, $res);
    }

    /**
     * @throws Exception
     */
    public function testGetOrder()
    {
        $res = $this->storeOrdersRepository->getOrder(1);
        $this->assertInstanceOf(StoreOrder::class, $res);
    }

    /**
     * @throws Exception
     */
    public function testGetProduct()
    {
        $res = $this->storeOrdersRepository->getProduct(1);
        $this->assertInstanceOf(StoreProduct::class, $res);
    }

    /**
     * @throws Exception
     */
    public function testDeleteOrder()
    {
        $res = $this->storeOrdersRepository->deleteOrder(100);
        $this->assertEquals(null, $res);
    }
}
