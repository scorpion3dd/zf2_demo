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

namespace AdminTest\Controller;

use Admin\Controller\StoreController;
use Exception;
use MobileTest\AbstractMock;

/**
 * Class StoreControllerTest
 * @package AdminTest\Controller
 */
class StoreControllerTest extends AbstractMock
{
    /**
     * @throws Exception
     */
    public function testGetIndexActionSuccess()
    {
        $this->dispatch('/admin/store', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('admin');
        $this->assertControllerName(StoreController::class);
        $this->assertControllerClass('StoreController');
        $this->assertMatchedRouteName('admin/store');
        $response = $this->getResponse()->getContent();
        self::assertTrue($this->HTMLValidate($response));
        $expected = file_get_contents(
            __DIR__ . '/../../test/data/Controller/Store/GetIndexActionSuccess.html'
        );
        self::assertSame($this->trim($expected), $this->trim($response));
    }

    /**
     * @throws Exception
     */
    public function testGetProductDetailActionSuccess()
    {
        $this->dispatch('/admin/store/product-detail/1', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('admin');
        $this->assertControllerName(StoreController::class);
        $this->assertControllerClass('StoreController');
        $this->assertMatchedRouteName('admin/store');
        $response = $this->getResponse()->getContent();
        $expected = file_get_contents(
            __DIR__ . '/../../test/data/Controller/Store/GetProductDetailActionSuccess.html'
        );
        self::assertSame($this->trim($expected), $this->trim($response));
    }

    /**
     * @throws Exception
     */
    public function testGetShoppingCartActionSuccess()
    {
        $this->dispatch('/admin/store/shopping-cart', 'POST', [
            'store_product_id' => '1',
            'qty' => '3',
        ]);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('admin');
        $this->assertControllerName(StoreController::class);
        $this->assertControllerClass('StoreController');
        $this->assertMatchedRouteName('admin/store');
        $response = $this->getResponse()->getContent();
        $expected = file_get_contents(
            __DIR__ . '/../../test/data/Controller/Store/PostShoppingCartActionSuccess.html'
        );
        self::assertSame($this->trim($expected), $this->trim($response));
    }

    /**
     * @throws Exception
     */
    public function testGetPaypalExpressCheckoutActionSuccess()
    {
        $this->dispatch('/admin/store/paypal-express-checkout', 'POST', [
            'orderId' => '1',
        ]);
        $this->assertResponseStatusCode(302);
        $this->assertModuleName('admin');
        $this->assertControllerName(StoreController::class);
        $this->assertControllerClass('StoreController');
        $this->assertMatchedRouteName('admin/store');
        $response = $this->getResponse()->getContent();
        self::assertSame('', $response);
    }

    /**
     * @throws Exception
     */
    public function testGetPaymentConfirmActionSuccess()
    {
        $this->dispatch('/admin/store/payment-confirm', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('admin');
        $this->assertControllerName(StoreController::class);
        $this->assertControllerClass('StoreController');
        $this->assertMatchedRouteName('admin/store');
        $response = $this->getResponse()->getContent();
        $expected = file_get_contents(
            __DIR__ . '/../../test/data/Controller/Store/PostPaymentConfirmActionSuccess.html'
        );
        self::assertSame($this->trim($expected), $this->trim($response));
    }

    /**
     * @throws Exception
     */
    public function testGetPaymentCancelActionSuccess()
    {
        $this->dispatch('/admin/store/payment-cancel', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('admin');
        $this->assertControllerName(StoreController::class);
        $this->assertControllerClass('StoreController');
        $this->assertMatchedRouteName('admin/store');
        $response = $this->getResponse()->getContent();
        $expected = file_get_contents(
            __DIR__ . '/../../test/data/Controller/Store/PostPaymentCancelActionSuccess.html'
        );
        self::assertSame($this->trim($expected), $this->trim($response));
    }

    /**
     * @throws Exception
     */
    public function testInvalidRoute()
    {
        $this->dispatch('/invalid/route', 'GET');
        $this->assertResponseStatusCode(404);
    }
}
