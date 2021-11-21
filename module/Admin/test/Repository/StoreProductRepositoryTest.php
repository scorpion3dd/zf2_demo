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

use Admin\Entity\StoreProduct;
use Admin\Repository\StoreProductRepository;
use Exception;
use MobileTest\AbstractMock;

/**
 * Class StoreProductRepositoryTest
 * @package AdminTest\Repository
 */
class StoreProductRepositoryTest extends AbstractMock
{
    private StoreProductRepository $storeProductsRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->storeProductsRepository = $this->serviceManager->get('StoreProductsTable');
    }

    /**
     * @throws Exception
     */
    public function testSaveProduct()
    {
        $product = new StoreProduct();
        $product->exchangeArray([
            'id' => '300',
            'name' => 'php name',
            'desc' => 'php desc',
            'cost' => 105,
        ]);
        $res = $this->storeProductsRepository->saveProduct($product);
        $this->assertEquals($product, $res);
    }

    /**
     * @throws Exception
     */
    public function testGetProduct()
    {
        $product = new StoreProduct();
        $product->exchangeArray([
            'id' => '1',
            'name' => 'Продукт 1',
            'desc' => 'классный продукт',
            'cost' => 100
        ]);
        $res = $this->storeProductsRepository->getProduct(1);
        $this->assertEquals($product, $res);
    }

    /**
     * @throws Exception
     */
    public function testDeleteProduct()
    {
        $res = $this->storeProductsRepository->deleteProduct(100);
        $this->assertEquals(null, $res);
    }
}
