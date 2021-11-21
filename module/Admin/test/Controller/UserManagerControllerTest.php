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

use Admin\Controller\UserManagerController;
use Exception;
use MobileTest\AbstractMock;

/**
 * Class UserManagerControllerTest
 * @package AdminTest\Controller
 */
class UserManagerControllerTest extends AbstractMock
{
    /**
     * @throws Exception
     */
    public function testGetIndexActionSuccess()
    {
        $this->dispatch('/admin/user-manager', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('admin');
        $this->assertControllerName(UserManagerController::class);
        $this->assertControllerClass('UserManagerController');
        $this->assertMatchedRouteName('admin/user-manager');
        $response = $this->getResponse()->getContent();
        self::assertTrue($this->HTMLValidate($response));
        $expected = file_get_contents(
            __DIR__ . '/../../test/data/Controller/UserManager/GetIndexActionSuccess.html'
        );
        self::assertSame($this->trim($expected), $this->trim($response));
    }

    /**
     * @throws Exception
     */
    public function testGetNewActionSuccess()
    {
        $this->dispatch('/admin/user-manager/new', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('admin');
        $this->assertControllerName(UserManagerController::class);
        $this->assertControllerClass('UserManagerController');
        $this->assertMatchedRouteName('admin/user-manager');
        $response = $this->getResponse()->getContent();
        $expected = file_get_contents(
            __DIR__ . '/../../test/data/Controller/UserManager/GetNewActionSuccess.html'
        );
        self::assertSame($this->trim($expected), $this->trim($response));
    }

    /**
     * @throws Exception
     */
    public function testGetEditActionSuccess()
    {
        $this->dispatch('/admin/user-manager/edit/1', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('admin');
        $this->assertControllerName(UserManagerController::class);
        $this->assertControllerClass('UserManagerController');
        $this->assertMatchedRouteName('admin/user-manager');
        $response = $this->getResponse()->getContent();
        $expected = file_get_contents(
            __DIR__ . '/../../test/data/Controller/UserManager/GetEditActionSuccess.html'
        );
        self::assertSame($this->trim($expected), $this->trim($response));
    }

    /**
     * @throws Exception
     */
    public function testGetProcessActionSuccess()
    {
        $this->dispatch('/admin/user-manager/process', 'GET');
        $this->assertResponseStatusCode(302);
        $this->assertModuleName('admin');
        $this->assertControllerName(UserManagerController::class);
        $this->assertControllerClass('UserManagerController');
        $this->assertMatchedRouteName('admin/user-manager');
        $response = $this->getResponse()->getContent();
        self::assertSame('', $response);
    }

    /**
     * @throws Exception
     */
    public function testPostProcessActionSuccess()
    {
        $this->dispatch('/admin/user-manager/process', 'POST', [
            'password' => 'qwerty',
            'confirmPassword' => 'qwerty',
            'name' => 'Ivan',
            'email' => 'ivan@gmail.com',
            'birthday' => '1985-07-30',
            'phone' => '0987779988',
            'address' => 'London',
        ]);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('admin');
        $this->assertControllerName(UserManagerController::class);
        $this->assertControllerClass('UserManagerController');
        $this->assertMatchedRouteName('admin/user-manager');
        $response = $this->getResponse()->getContent();
        $expected = file_get_contents(
            __DIR__ . '/../../test/data/Controller/UserManager/PostProcessActionSuccess.html'
        );
        self::assertSame($this->trim($expected), $this->trim($response));
    }

    /**
     * @throws Exception
     */
    public function testGetDeleteActionSuccess()
    {
        $this->dispatch('/admin/user-manager/delete/100', 'GET');
        $this->assertResponseStatusCode(302);
        $this->assertModuleName('admin');
        $this->assertControllerName(UserManagerController::class);
        $this->assertControllerClass('UserManagerController');
        $this->assertMatchedRouteName('admin/user-manager');
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
