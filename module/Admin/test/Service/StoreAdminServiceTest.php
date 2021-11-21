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

use Admin\Entity\StoreProduct;
use Admin\Service\StoreAdminService;
use Exception;
use MobileTest\AbstractMock;

/**
 * Class StoreAdminServiceTest
 * @package AdminTest\Service
 */
class StoreAdminServiceTest extends AbstractMock
{
    private StoreAdminService $storeAdminService;

    public function setUp(): void
    {
        parent::setUp();

        $this->storeAdminService = $this->serviceManager->get('StoreAdminService');
    }

    /**
     * @throws Exception
     */
    public function testGetStoreOrdersTable()
    {
        $storeOrderRepository = $this->serviceManager->get('StoreOrdersTable');
        $res = $this->storeAdminService->getStoreOrdersTable();
        $this->assertEquals($storeOrderRepository, $res);
    }

    /**
     * @throws Exception
     */
    public function testGetProductsTable()
    {
        $storeProductsTable = $this->serviceManager->get('StoreProductsTable');
        $res = $this->storeAdminService->getStoreProductsTable();
        $this->assertEquals($storeProductsTable, $res);
    }

    /**
     * @throws Exception
     */
    public function testGetStoreOrdersTableGateway()
    {
        $storeOrdersTableGateway = $this->serviceManager->get('StoreOrdersTableGateway');
        $res = $this->storeAdminService->getStoreOrdersTableGateway();
        $this->assertEquals($storeOrdersTableGateway, $res);
    }

    /**
     * @throws Exception
     */
    public function testCreateProduct()
    {
        $storeProduct = new StoreProduct();
        $storeProduct->id = 7;
        $res = $this->storeAdminService->createProduct([]);
        $this->assertEquals($storeProduct, $res);
    }
}
