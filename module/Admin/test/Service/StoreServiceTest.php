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

namespace AdminTest\Service;

use Admin\Entity\StoreOrder;
use Admin\Entity\StoreProduct;
use Admin\Service\StoreService;
use Exception;
use MobileTest\AbstractMock;

/**
 * Class StoreServiceTest
 * @package AdminTest\Service
 */
class StoreServiceTest extends AbstractMock
{
    private StoreService $storeService;

    public function setUp(): void
    {
        parent::setUp();

        $this->storeService = $this->serviceManager->get('StoreService');
    }

    /**
     * @throws Exception
     */
    public function testGetStoreOrdersTable()
    {
        $storeOrderRepository = $this->serviceManager->get('StoreOrdersTable');
        $res = $this->storeService->getStoreOrdersTable();
        $this->assertEquals($storeOrderRepository, $res);
    }

    /**
     * @throws Exception
     */
    public function testGetStoreProductsTable()
    {
        $storeProductRepository = $this->serviceManager->get('StoreProductsTable');
        $res = $this->storeService->getStoreProductsTable();
        $this->assertEquals($storeProductRepository, $res);
    }

    /**
     * @throws Exception
     */
    public function testGetStoreOrdersTableGateway()
    {
        $storeOrdersTableGateway = $this->serviceManager->get('StoreOrdersTableGateway');
        $res = $this->storeService->getStoreOrdersTableGateway();
        $this->assertEquals($storeOrdersTableGateway, $res);
    }

    /**
     * @throws Exception
     */
    public function testCreateProduct()
    {
        $storeProduct = new StoreProduct();
        $storeProduct->id = 8;
        $res = $this->storeService->createProduct([]);
        $this->assertEquals($storeProduct, $res);
    }

    /**
     * @throws Exception
     */
    public function testGetPaypalRequest()
    {
        $res = $this->storeService->getPaypalRequest();
        $this->assertEquals(null, $res);
    }

    /**
     * @throws Exception
     */
    public function testGetPaypalExpressCheckout()
    {
        $res = $this->storeService->getPaypalExpressCheckout(new StoreOrder());
        $this->assertEquals('/admin/store', $res);
    }

    /**
     * @throws Exception
     */
    public function testPaymentConfirm()
    {
        $res = $this->storeService->paymentConfirm();
        $this->assertEquals(new StoreOrder(new StoreProduct()), $res);
    }
}
