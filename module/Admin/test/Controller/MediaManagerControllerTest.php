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

use Admin\Controller\MediaManagerController;
use Exception;
use MobileTest\AbstractMock;
use Zend\Stdlib\Parameters;

/**
 * Class MediaManagerControllerTest
 * @package AdminTest\Controller
 */
class MediaManagerControllerTest extends AbstractMock
{
    /**
     * @throws Exception
     */
    public function testGetIndexActionSuccess()
    {
        $this->dispatch('/admin/media', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('admin');
        $this->assertControllerName(MediaManagerController::class);
        $this->assertControllerClass('MediaManagerController');
        $this->assertMatchedRouteName('admin/media');
        $response = $this->getResponse()->getContent();
        self::assertTrue($this->HTMLValidate($response));
        $expected = file_get_contents(
            __DIR__ . '/../../test/data/Controller/MediaManager/GetIndexActionSuccess.html'
        );
        self::assertSame($this->trim($expected), $this->trim($response));
    }

    /**
     * @throws Exception
     */
    public function testGetProcessUploadActionSuccess()
    {
        $this->dispatch('/admin/media/process-upload', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('admin');
        $this->assertControllerName(MediaManagerController::class);
        $this->assertControllerClass('MediaManagerController');
        $this->assertMatchedRouteName('admin/media');
        $response = $this->getResponse()->getContent();
        self::assertTrue($this->HTMLValidate($response));
        $expected = file_get_contents(
            __DIR__ . '/../../test/data/Controller/MediaManager/GetProcessUploadActionSuccess.html'
        );
        self::assertSame($this->trim($expected), $this->trim($response));
    }

    /**
     * @throws Exception
     */
    public function testPostProcessUploadActionSuccess()
    {
        $dataForCsv = [
            [
                "FF08H3456",
                "Elon",
                "Musk",
                "Tesla",
                "USA, Boca Chica, Cameron County, Texas",
                "223-322-223",
                "elon.musk@gmail.com",
                "custom value 1",
                "custom value 2",
                "custom value 3"
            ],
            [
                "WB08H4554",
                "John",
                "Lennon",
                "Rolls-Royce",
                "USA, New York",
                "111-222-333",
                "john.lennon@gmail.com",
                "custom value 11",
                "custom value 22",
                "custom value 33"
            ]
        ];

        $file = fopen(__DIR__ . '/../../test/data/Controller/MediaManager/info.csv', "w");
        foreach ($dataForCsv as $line) {
            fputcsv($file, $line);
        }
        fclose($file);

        $_FILES = [
            'file' => [
                'name' => 'info.csv',
                'type' => 'text/csv',
                'tmp_name' => __DIR__ . '/../../test/data/Controller/MediaManager/',
                'error' => 0,
                'size' => 402
            ]
        ];
        $upload = new Parameters($_FILES);
        $this->getRequest()->setFiles($upload);
        $this->dispatch('/admin/media/process-upload', 'POST');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('admin');
        $this->assertControllerName(MediaManagerController::class);
        $this->assertControllerClass('MediaManagerController');
        $this->assertMatchedRouteName('admin/media');
        $response = $this->getResponse()->getContent();
        self::assertTrue($this->HTMLValidate($response));
        $expected = file_get_contents(
            __DIR__ . '/../../test/data/Controller/MediaManager/PostProcessUploadActionSuccess.html'
        );
        self::assertSame($this->trim($expected), $this->trim($response));
    }

    /**
     * @throws Exception
     */
    public function testGetUploadActionSuccess()
    {
        $this->dispatch('/admin/media/upload', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('admin');
        $this->assertControllerName(MediaManagerController::class);
        $this->assertControllerClass('MediaManagerController');
        $this->assertMatchedRouteName('admin/media');
        $response = $this->getResponse()->getContent();
        self::assertTrue($this->HTMLValidate($response));
        $expected = file_get_contents(
            __DIR__ . '/../../test/data/Controller/MediaManager/GetUploadActionSuccess.html'
        );
        self::assertSame($this->trim($expected), $this->trim($response));
    }

    /**
     * @throws Exception
     */
    public function testGetDeleteActionSuccess()
    {
        $this->dispatch('/admin/media/delete/2', 'GET');
        $this->assertResponseStatusCode(302);
        $this->assertModuleName('admin');
        $this->assertControllerName(MediaManagerController::class);
        $this->assertControllerClass('MediaManagerController');
        $this->assertMatchedRouteName('admin/media');
        $response = $this->getResponse()->getContent();
        self::assertSame('', $response);
    }

    /**
     * @throws Exception
     */
    public function testGetViewActionSuccess()
    {
        $this->dispatch('/admin/media/view/1', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('admin');
        $this->assertControllerName(MediaManagerController::class);
        $this->assertControllerClass('MediaManagerController');
        $this->assertMatchedRouteName('admin/media');
        $response = $this->getResponse()->getContent();
        self::assertTrue($this->HTMLValidate($response));
        $expected = file_get_contents(
            __DIR__ . '/../../test/data/Controller/MediaManager/GetViewActionSuccess.html'
        );
        self::assertSame($this->trim($expected), $this->trim($response));
    }

    /**
     * @throws Exception
     */
    public function testGetShowImageActionSuccess()
    {
        $this->dispatch('/admin/media/show-image/1/thumb', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('admin');
        $this->assertControllerName(MediaManagerController::class);
        $this->assertControllerClass('MediaManagerController');
        $this->assertMatchedRouteName('admin/media');
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
