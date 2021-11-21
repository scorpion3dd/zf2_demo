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

namespace Admin\Service;

use Admin\Entity\StoreProduct;
use Admin\Repository\StoreOrderRepository;
use Admin\Repository\StoreProductRepository;
use Exception;
use Zend\Db\TableGateway\TableGateway;

/**
 * Class StoreAdminService
 * @package Admin\Service
 */
class StoreAdminService extends AbstractService
{
    private StoreOrderRepository $storeOrdersTable;
    private StoreProductRepository $storeProductsTable;
    private TableGateway $storeOrdersTableGateway;

    /**
     * StoreAdminService constructor.
     * @param array<array> $config
     * @param StoreOrderRepository $storeOrdersTable
     * @param StoreProductRepository $storeProductsTable
     * @param TableGateway $storeOrdersTableGateway
     */
    public function __construct(
        array                  $config,
        StoreOrderRepository   $storeOrdersTable,
        StoreProductRepository $storeProductsTable,
        TableGateway           $storeOrdersTableGateway
    ) {
        parent::__construct($config);
        $this->storeOrdersTable = $storeOrdersTable;
        $this->storeProductsTable = $storeProductsTable;
        $this->storeOrdersTableGateway = $storeOrdersTableGateway;
    }

    /**
     * @return StoreOrderRepository
     */
    public function getStoreOrdersTable(): StoreOrderRepository
    {
        return $this->storeOrdersTable;
    }

    /**
     * @return StoreProductRepository
     */
    public function getStoreProductsTable(): StoreProductRepository
    {
        return $this->storeProductsTable;
    }

    /**
     * @return TableGateway
     */
    public function getStoreOrdersTableGateway(): TableGateway
    {
        return $this->storeOrdersTableGateway;
    }

    /**
     * @param array<array> $data
     *
     * @return StoreProduct
     * @throws Exception
     */
    public function createProduct(array $data): StoreProduct
    {
        $product = new StoreProduct();
        $product->exchangeArray($data);

        return $this->getStoreProductsTable()->saveProduct($product);
    }
}
