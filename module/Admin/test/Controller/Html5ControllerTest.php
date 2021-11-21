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

use Admin\Controller\Html5Controller;
use Exception;
use MobileTest\AbstractMock;

/**
 * Class Html5ControllerTest
 * @package AdminTest\Controller
 */
class Html5ControllerTest extends AbstractMock
{
    /**
     * @throws Exception
     */
    public function testIndexActionSuccess()
    {
        $this->dispatch('/admin/html5', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('admin');
        $this->assertControllerName(Html5Controller::class);
        $this->assertControllerClass('Html5Controller');
        $this->assertMatchedRouteName('admin/html5');
        $response = $this->getResponse()->getContent();
        self::assertTrue($this->HTMLValidate($response));
        $expected = file_get_contents(
            __DIR__ . '/../../test/data/Controller/Html5/GetIndexActionSuccess.html'
        );
        self::assertSame($this->trim($expected), $this->trim($response));
    }

    /**
     * @throws Exception
     */
    public function testFormActionSuccess()
    {
        $this->dispatch('/admin/html5/form', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('admin');
        $this->assertControllerName(Html5Controller::class);
        $this->assertControllerClass('Html5Controller');
        $this->assertMatchedRouteName('admin/html5');
        $response = $this->getResponse()->getContent();
        $expected = file_get_contents(
            __DIR__ . '/../../test/data/Controller/Html5/GetFormActionSuccess.html'
        );
        self::assertSame($this->trim($expected), $this->trim($response));
    }

    /**
     * @throws Exception
     */
    public function testMultiUploadActionSuccess()
    {
        $this->dispatch('/admin/html5/multiUpload', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('admin');
        $this->assertControllerName(Html5Controller::class);
        $this->assertControllerClass('Html5Controller');
        $this->assertMatchedRouteName('admin/html5');
        $response = $this->getResponse()->getContent();
        self::assertTrue($this->HTMLValidate($response));
        $expected = file_get_contents(
            __DIR__ . '/../../test/data/Controller/Html5/GetMultiUploadActionSuccess.html'
        );
        self::assertSame($this->trim($expected), $this->trim($response));
    }

    /**
     * @throws Exception
     */
    public function testProcessMultiUploadActionSuccess()
    {
        $this->dispatch('/admin/html5/processMultiUpload', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('admin');
        $this->assertControllerName(Html5Controller::class);
        $this->assertControllerClass('Html5Controller');
        $this->assertMatchedRouteName('admin/html5');
        $response = $this->getResponse()->getContent();
        self::assertTrue($this->HTMLValidate($response));
        $expected = file_get_contents(
            __DIR__ . '/../../test/data/Controller/Html5/GetProcessMultiUploadActionSuccess.html'
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
