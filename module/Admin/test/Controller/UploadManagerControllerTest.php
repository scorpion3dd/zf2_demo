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

use Admin\Controller\UploadManagerController;
use Exception;
use MobileTest\AbstractMock;

/**
 * Class UploadManagerControllerTest
 * @package AdminTest\Controller
 */
class UploadManagerControllerTest extends AbstractMock
{
    /**
     * @throws Exception
     */
    public function testGetIndexActionSuccess()
    {
        $this->dispatch('/admin/upload-manager', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('admin');
        $this->assertControllerName(UploadManagerController::class);
        $this->assertControllerClass('UploadManagerController');
        $this->assertMatchedRouteName('admin/upload-manager');
        $response = $this->getResponse()->getContent();
        self::assertTrue($this->HTMLValidate($response));
        $expected = file_get_contents(
            __DIR__ . '/../../test/data/Controller/UploadManager/GetIndexActionSuccess.html'
        );
        self::assertSame($this->trim($expected), $this->trim($response));
    }

    /**
     * @throws Exception
     */
    public function testGetProcessUploadActionSuccess()
    {
        $this->dispatch('/admin/upload-manager/process-upload', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('admin');
        $this->assertControllerName(UploadManagerController::class);
        $this->assertControllerClass('UploadManagerController');
        $this->assertMatchedRouteName('admin/upload-manager');
        $response = $this->getResponse()->getContent();
        self::assertTrue($this->HTMLValidate($response));
        $expected = file_get_contents(
            __DIR__ . '/../../test/data/Controller/UploadManager/GetProcessUploadActionSuccess.html'
        );
        self::assertSame($this->trim($expected), $this->trim($response));
    }

    /**
     * @throws Exception
     */
    public function testGetUploadActionSuccess()
    {
        $this->dispatch('/admin/upload-manager/upload', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('admin');
        $this->assertControllerName(UploadManagerController::class);
        $this->assertControllerClass('UploadManagerController');
        $this->assertMatchedRouteName('admin/upload-manager');
        $response = $this->getResponse()->getContent();
        $expected = file_get_contents(
            __DIR__ . '/../../test/data/Controller/UploadManager/GetUploadActionSuccess.html'
        );
        self::assertSame($this->trim($expected), $this->trim($response));
    }

    /**
     * @throws Exception
     */
    public function testGetProcessUploadEditActionSuccess()
    {
        $this->dispatch('/admin/upload-manager/process-upload-edit', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('admin');
        $this->assertControllerName(UploadManagerController::class);
        $this->assertControllerClass('UploadManagerController');
        $this->assertMatchedRouteName('admin/upload-manager');
        $response = $this->getResponse()->getContent();
        $expected = file_get_contents(
            __DIR__ . '/../../test/data/Controller/UploadManager/GetProcessUploadEditActionSuccess.html'
        );
        self::assertSame($this->trim($expected), $this->trim($response));
    }

    /**
     * @throws Exception
     */
    public function testGetDeleteActionSuccess()
    {
        $this->dispatch('/admin/upload-manager/delete/1', 'GET');
        $this->assertResponseStatusCode(302);
        $this->assertModuleName('admin');
        $this->assertControllerName(UploadManagerController::class);
        $this->assertControllerClass('UploadManagerController');
        $this->assertMatchedRouteName('admin/upload-manager');
        $response = $this->getResponse()->getContent();
        self::assertSame('', $response);
    }

    /**
     * @throws Exception
     */
    public function testGetEditActionSuccess()
    {
        $this->dispatch('/admin/upload-manager/edit/1', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('admin');
        $this->assertControllerName(UploadManagerController::class);
        $this->assertControllerClass('UploadManagerController');
        $this->assertMatchedRouteName('admin/upload-manager');
        $response = $this->getResponse()->getContent();
        $expected = file_get_contents(
            __DIR__ . '/../../test/data/Controller/UploadManager/GetEditActionSuccess.html'
        );
//        self::assertSame($this->trim($expected), $this->trim($response));
    }

    /**
     * @throws Exception
     */
    public function testGetProcessUploadShareActionSuccess()
    {
        $this->dispatch('/admin/upload-manager/process-upload-share', 'POST', [
            'user_id' => 1,
            'upload_id' => 2,
        ]);
        $this->assertResponseStatusCode(302);
        $this->assertModuleName('admin');
        $this->assertControllerName(UploadManagerController::class);
        $this->assertControllerClass('UploadManagerController');
        $this->assertMatchedRouteName('admin/upload-manager');
        $response = $this->getResponse()->getContent();
        self::assertSame('', $response);
    }

    /**
     * @throws Exception
     */
    public function testGetFileDownloadActionSuccess()
    {
        $this->dispatch('/admin/upload-manager/file-download/1', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('admin');
        $this->assertControllerName(UploadManagerController::class);
        $this->assertControllerClass('UploadManagerController');
        $this->assertMatchedRouteName('admin/upload-manager');
        $response = $this->getResponse()->getContent();
        self::assertFalse($response);
    }

    /**
     * @throws Exception
     */
    public function testGetDeleteShareeActionSuccess()
    {
        $this->dispatch('/admin/upload-manager/delete-share/1-2', 'GET');
        $this->assertResponseStatusCode(302);
        $this->assertModuleName('admin');
        $this->assertControllerName(UploadManagerController::class);
        $this->assertControllerClass('UploadManagerController');
        $this->assertMatchedRouteName('admin/upload-manager');
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
