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

use Admin\Controller\LoginController;
use Exception;
use MobileTest\AbstractMock;

/**
 * Class LoginControllerTest
 * @package AdminTest\Controller
 */
class LoginControllerTest extends AbstractMock
{
    /**
     * @throws Exception
     */
    public function testIndexActionSuccess()
    {
        $this->dispatch('/admin/login', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('admin');
        $this->assertControllerName(LoginController::class);
        $this->assertControllerClass('LoginController');
        $this->assertMatchedRouteName('admin/login');
        $response = $this->getResponse()->getContent();
        self::assertTrue($this->HTMLValidate($response));
        $expected = file_get_contents(
            __DIR__ . '/../../test/data/Controller/Login/GetIndexActionSuccess.html'
        );
        self::assertSame($this->trim($expected), $this->trim($response));
    }

    /**
     * @throws Exception
     */
    public function testLogoutActionSuccess()
    {
        $this->dispatch('/admin/login/logout', 'GET');
        $this->assertResponseStatusCode(302);
        $this->assertModuleName('admin');
        $this->assertControllerName(LoginController::class);
        $this->assertControllerClass('LoginController');
        $this->assertMatchedRouteName('admin/login');
        $response = $this->getResponse()->getContent();
        self::assertSame("", $response);
    }

    /**
     * @throws Exception
     */
    public function testProcessActionSuccess()
    {
        $this->dispatch('/admin/login/process', 'POST', [
            'email' => 'admin@mail.ru',
            'password' => 'admin',
        ]);
        $this->assertResponseStatusCode(302);
        $this->assertModuleName('admin');
        $this->assertControllerName(LoginController::class);
        $this->assertControllerClass('LoginController');
        $this->assertMatchedRouteName('admin/login');
        $response = $this->getResponse()->getContent();
        self::assertSame("", $response);
    }

    /**
     * @throws Exception
     */
    public function testConfirmActionSuccess()
    {
        $this->dispatch('/admin/login/confirm', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('admin');
        $this->assertControllerName(LoginController::class);
        $this->assertControllerClass('LoginController');
        $this->assertMatchedRouteName('admin/login');
        $response = $this->getResponse()->getContent();
        self::assertTrue($this->HTMLValidate($response));
        $expected = file_get_contents(
            __DIR__ . '/../../test/data/Controller/Login/GetConfirmActionSuccess.html'
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
