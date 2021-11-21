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

use Admin\Entity\StoreProduct;
use MobileTest\AbstractMock;

/**
 * Class StoreProductTest
 * @package AdminTest\Entity
 */
class StoreProductTest extends AbstractMock
{
    public function testExchangeArray()
    {
        $id = 11;
        $name = 'Table';
        $desc = 'Office table';
        $cost = 1050;
        $storeProduct = new StoreProduct();
        $storeProduct->exchangeArray([
            'id' => $id,
            'name' => $name,
            'desc' => $desc,
            'cost' => $cost,
        ]);
        $this->assertSame($id, $storeProduct->id);
        $this->assertSame($name, $storeProduct->name);
        $this->assertSame($desc, $storeProduct->desc);
        $this->assertSame($cost, $storeProduct->cost);
    }
}
