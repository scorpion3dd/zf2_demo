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

use Admin\Entity\StoreProduct;
use Exception;
use Zend\Db\TableGateway\TableGateway;

/**
 * Class StoreProductRepository
 * @package Admin\Repository
 */
class StoreProductRepository extends AbstractRepository
{
    /**
     * StoreProductRepository constructor.
     * @param TableGateway $tableGateway
     */
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    /**
     * @param StoreProduct $product
     *
     * @return StoreProduct
     * @throws Exception
     */
    public function saveProduct(StoreProduct $product): StoreProduct
    {
        $data = [
            'name' => $product->name,
            'desc' => $product->desc,
            'cost'  => $product->cost,
        ];

        $id = (int)$product->id;
        if ($id == 0) {
            $this->getTableGateway()->insert($data);
            $product->id = (int)$this->getTableGateway()->lastInsertValue;
        } else {
            $this->getTableGateway()->update($data, ['id' => $id]);
        }

        return $product;
    }

    /**
     * @param int $productId
     *
     * @return StoreProduct
     * @throws Exception
     */
    public function getProduct(int $productId): StoreProduct
    {
        $rowset = $this->getTableGateway()->select(['id' => $productId]);
        /** @var StoreProduct $product */
        /** @phpstan-ignore-next-line */
        $product = $rowset->current();

        return $product;
    }

    /**
     * @param int $productId
     */
    public function deleteProduct(int $productId): void
    {
        $this->getTableGateway()->delete(['id' => $productId]);
    }
}
