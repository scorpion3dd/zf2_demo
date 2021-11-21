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

use Admin\Controller\StoreAdminController;
use Exception;
use MobileTest\AbstractMock;

/**
 * Class StoreAdminControllerTest
 * @package AdminTest\Controller
 */
class StoreAdminControllerTest extends AbstractMock
{
    /**
     * @throws Exception
     */
    public function testGetIndexActionSuccess()
    {
        $this->dispatch('/admin/store-admin', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('admin');
        $this->assertControllerName(StoreAdminController::class);
        $this->assertControllerClass('StoreAdminController');
        $this->assertMatchedRouteName('admin/store-admin');
        $response = $this->getResponse()->getContent();
        self::assertTrue($this->HTMLValidate($response));
        $expected = file_get_contents(
            __DIR__ . '/../../test/data/Controller/StoreAdmin/GetIndexActionSuccess.html'
        );
        self::assertSame($this->trim($expected), $this->trim($response));
    }

    /**
     * @throws Exception
     */
    public function testGetListOrdersActionSuccess()
    {
        $this->dispatch('/admin/store-admin/list-orders', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('admin');
        $this->assertControllerName(StoreAdminController::class);
        $this->assertControllerClass('StoreAdminController');
        $this->assertMatchedRouteName('admin/store-admin');
        $response = $this->getResponse()->getContent();
        self::assertTrue($this->HTMLValidate($response));
        $expected = file_get_contents(
            __DIR__ . '/../../test/data/Controller/StoreAdmin/GetListOrdersActionSuccess.html'
        );
        self::assertSame($this->trim($expected), $this->trim($response));
    }

    /**
     * @throws Exception
     */
    public function testGetViewOrderActionSuccess()
    {
        $this->dispatch('/admin/store-admin/view-order/1', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('admin');
        $this->assertControllerName(StoreAdminController::class);
        $this->assertControllerClass('StoreAdminController');
        $this->assertMatchedRouteName('admin/store-admin');
        $response = $this->getResponse()->getContent();
        $expected = file_get_contents(
            __DIR__ . '/../../test/data/Controller/StoreAdmin/GetViewOrderActionSuccess.html'
        );
        self::assertSame($this->trim($expected), $this->trim($response));
    }

    /**
     * @throws Exception
     */
    public function testGetConfirmAddProductActionSuccess()
    {
        $this->dispatch('/admin/store-admin/confirm-add-product?name=Ivan&productId=7', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('admin');
        $this->assertControllerName(StoreAdminController::class);
        $this->assertControllerClass('StoreAdminController');
        $this->assertMatchedRouteName('admin/store-admin');
        $response = $this->getResponse()->getContent();
        self::assertTrue($this->HTMLValidate($response));
        $expected = file_get_contents(
            __DIR__ . '/../../test/data/Controller/StoreAdmin/GetConfirmAddProductActionSuccess.html'
        );
        self::assertSame($this->trim($expected), $this->trim($response));
    }

    /**
     * @throws Exception
     */
    public function testGetNewProductActionSuccess()
    {
        $this->dispatch('/admin/store-admin/new-product', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('admin');
        $this->assertControllerName(StoreAdminController::class);
        $this->assertControllerClass('StoreAdminController');
        $this->assertMatchedRouteName('admin/store-admin');
        $response = $this->getResponse()->getContent();
        $expected = file_get_contents(
            __DIR__ . '/../../test/data/Controller/StoreAdmin/GetNewProductActionSuccess.html'
        );
        self::assertSame($this->trim($expected), $this->trim($response));
    }

    /**
     * @throws Exception
     */
    public function testPostNewProductActionSuccess()
    {
        $this->dispatch('/admin/store-admin/new-product', 'POST', [
            'name' => 'Product 001',
            'desc' => 'Description',
            'cost' => '123',
        ]);
        $this->assertResponseStatusCode(302);
        $this->assertModuleName('admin');
        $this->assertControllerName(StoreAdminController::class);
        $this->assertControllerClass('StoreAdminController');
        $this->assertMatchedRouteName('admin/store-admin');
        $response = $this->getResponse()->getContent();
        self::assertSame('', $response);
    }

    /**
     * @throws Exception
     */
    public function testGetEditProductActionSuccess()
    {
        $this->dispatch('/admin/store-admin/edit-product/1', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('admin');
        $this->assertControllerName(StoreAdminController::class);
        $this->assertControllerClass('StoreAdminController');
        $this->assertMatchedRouteName('admin/store-admin');
        $response = $this->getResponse()->getContent();
        $expected = file_get_contents(
            __DIR__ . '/../../test/data/Controller/StoreAdmin/GetEditProductActionSuccess.html'
        );
        self::assertSame($this->trim($expected), $this->trim($response));
    }

    /**
     * @throws Exception
     */
    public function testPostEditProductActionSuccess()
    {
        $this->dispatch('/admin/store-admin/edit-product', 'POST', [
            'id' => 1,
            'name' => 'Product 001',
            'desc' => 'Description',
            'cost' => '123',
        ]);
        $this->assertResponseStatusCode(302);
        $this->assertModuleName('admin');
        $this->assertControllerName(StoreAdminController::class);
        $this->assertControllerClass('StoreAdminController');
        $this->assertMatchedRouteName('admin/store-admin');
        $response = $this->getResponse()->getContent();
        self::assertSame('', $response);
    }

    /**
     * @throws Exception
     */
    public function testGetUpdateOrderStatusActionSuccess()
    {
        $this->dispatch('/admin/store-admin/update-order-status/1', 'GET');
        $this->assertResponseStatusCode(302);
        $this->assertModuleName('admin');
        $this->assertControllerName(StoreAdminController::class);
        $this->assertControllerClass('StoreAdminController');
        $this->assertMatchedRouteName('admin/store-admin');
        $response = $this->getResponse()->getContent();
        self::assertSame('', $response);
    }

    /**
     * @throws Exception
     */
    public function testGetDeleteProductActionSuccess()
    {
        $this->dispatch('/admin/store-admin/delete-product/1', 'GET');
        $this->assertResponseStatusCode(302);
        $this->assertModuleName('admin');
        $this->assertControllerName(StoreAdminController::class);
        $this->assertControllerClass('StoreAdminController');
        $this->assertMatchedRouteName('admin/store-admin');
        $response = $this->getResponse()->getContent();
        self::assertSame('', $response);
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
