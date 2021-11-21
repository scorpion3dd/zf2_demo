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

use Admin\Controller\RegisterController;
use Exception;
use MobileTest\AbstractMock;

/**
 * Class RegisterControllerTest
 * @package AdminTest\Controller
 */
class RegisterControllerTest extends AbstractMock
{
    /**
     * @throws Exception
     */
    public function testIndexActionSuccess()
    {
        $this->dispatch('/admin/register', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('admin');
        $this->assertControllerName(RegisterController::class);
        $this->assertControllerClass('RegisterController');
        $this->assertMatchedRouteName('admin/register');
        $response = $this->getResponse()->getContent();
        self::assertTrue($this->HTMLValidate($response));
        $expected = file_get_contents(
            __DIR__ . '/../../test/data/Controller/Register/GetIndexActionSuccess.html'
        );
        self::assertSame($this->trim($expected), $this->trim($response));
    }

    /**
     * @throws Exception
     */
    public function testProcessActionSuccess()
    {
        $this->dispatch('/admin/register/process', 'POST', [
            'name' => 'admin30',
            'email' => 'admin30@gmail.com',
            'password' => 'admin30',
            'confirmPassword' => 'admin30',
        ]);
        $this->assertResponseStatusCode(302);
        $this->assertModuleName('admin');
        $this->assertControllerName(RegisterController::class);
        $this->assertControllerClass('RegisterController');
        $this->assertMatchedRouteName('admin/register');
        $response = $this->getResponse()->getContent();
        self::assertSame("", $response);
    }

    /**
     * @throws Exception
     */
    public function testConfirmActionSuccess()
    {
        $this->dispatch('/admin/register/confirm', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('admin');
        $this->assertControllerName(RegisterController::class);
        $this->assertControllerClass('RegisterController');
        $this->assertMatchedRouteName('admin/register');
        $response = $this->getResponse()->getContent();
        self::assertTrue($this->HTMLValidate($response));
        $expected = file_get_contents(
            __DIR__ . '/../../test/data/Controller/Register/GetConfirmActionSuccess.html'
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
