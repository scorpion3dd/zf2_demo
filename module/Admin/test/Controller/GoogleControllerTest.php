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

use Admin\Controller\GoogleController;
use Exception;
use MobileTest\AbstractMock;

/**
 * Class GoogleControllerTest
 * @package AdminTest\Controller
 */
class GoogleControllerTest extends AbstractMock
{
    /**
     * @throws Exception
     */
    public function testIndexActionSuccess()
    {
        $this->dispatch('/admin/google', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('admin');
        $this->assertControllerName(GoogleController::class);
        $this->assertControllerClass('GoogleController');
        $this->assertMatchedRouteName('admin/google');
        $response = $this->getResponse()->getContent();
        self::assertTrue($this->HTMLValidate($response));
        $expected = file_get_contents(
            __DIR__ . '/../../test/data/Controller/Google/GetIndexActionSuccess.html'
        );
        self::assertSame($this->trim($expected), $this->trim($response));
    }

    /**
     * @throws Exception
     */
    public function testUploadPhotoActionSuccess()
    {
        $this->dispatch('/admin/google/upload-photo/ABYfwkxqw7shMPIixV_ZpBGqQkvrg6IgnTa5Lotv9lG5kofmAae3L2hxFc9xSjELTukhD-hNSjgO', 'POST');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('admin');
        $this->assertControllerName(GoogleController::class);
        $this->assertControllerClass('GoogleController');
        $this->assertMatchedRouteName('admin/google');
        $response = $this->getResponse()->getContent();
        $expected = file_get_contents(
            __DIR__ . '/../../test/data/Controller/Google/PostUploadPhotoActionSuccess.html'
        );
        self::assertSame($this->trim($expected), $this->trim($response));
    }

    /**
     * @throws Exception
     */
    public function testConfirmUploadPhotoActionSuccess()
    {
        $this->dispatch('/admin/google/confirm-upload-photo', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('admin');
        $this->assertControllerName(GoogleController::class);
        $this->assertControllerClass('GoogleController');
        $this->assertMatchedRouteName('admin/google');
        $response = $this->getResponse()->getContent();
        self::assertTrue($this->HTMLValidate($response));
        $expected = file_get_contents(
            __DIR__ . '/../../test/data/Controller/Google/GetConfirmUploadPhotoActionSuccess.html'
        );
        self::assertSame($this->trim($expected), $this->trim($response));
    }

    /**
     * @throws Exception
     */
    public function testNewAlbumActionSuccess()
    {
        $this->dispatch('/admin/google/confirm-upload-photo', 'POST');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('admin');
        $this->assertControllerName(GoogleController::class);
        $this->assertControllerClass('GoogleController');
        $this->assertMatchedRouteName('admin/google');
        $response = $this->getResponse()->getContent();
        self::assertTrue($this->HTMLValidate($response));
        $expected = file_get_contents(
            __DIR__ . '/../../test/data/Controller/Google/PostNewAlbumActionSuccess.html'
        );
        self::assertSame($this->trim($expected), $this->trim($response));
    }

    /**
     * @throws Exception
     */
    public function testConfirmAddAlbumActionSuccess()
    {
        $this->dispatch('/admin/google/confirm-upload-photo', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('admin');
        $this->assertControllerName(GoogleController::class);
        $this->assertControllerClass('GoogleController');
        $this->assertMatchedRouteName('admin/google');
        $response = $this->getResponse()->getContent();
        self::assertTrue($this->HTMLValidate($response));
        $expected = file_get_contents(
            __DIR__ . '/../../test/data/Controller/Google/GetConfirmAddAlbumActionSuccess.html'
        );
        self::assertSame($this->trim($expected), $this->trim($response));
    }

    /**
     * @throws Exception
     */
    public function testPostBooksActionSuccess()
    {
        $this->dispatch('/admin/google/books', 'POST', ['words' => 'PHP']);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('admin');
        $this->assertControllerName(GoogleController::class);
        $this->assertControllerClass('GoogleController');
        $this->assertMatchedRouteName('admin/google');
        $response = $this->getResponse()->getContent();
        self::assertTrue(strlen($response) > 0);
    }

    /**
     * @throws Exception
     */
    public function testGetBooksActionSuccess()
    {
        $this->dispatch('/admin/google/books', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('admin');
        $this->assertControllerName(GoogleController::class);
        $this->assertControllerClass('GoogleController');
        $this->assertMatchedRouteName('admin/google');
        $response = $this->getResponse()->getContent();
        $expected = file_get_contents(
            __DIR__ . '/../../test/data/Controller/Google/GetBooksActionSuccess.html'
        );
        self::assertSame($this->trim($expected), $this->trim($response));
    }

    /**
     * @throws Exception
     */
    public function testGetAlbumsActionSuccess()
    {
        $this->HTMLValidate('<!DOCTYPE html>', false);
        if (AbstractMock::$validation) {
            putenv('GOOGLE_CLIENT_ID=1111');
            putenv('GOOGLE_CLIENT_SECRET=1111');
            putenv('GOOGLE_PROJECT_ID=1111');
            putenv('GOOGLE_REDIRECT_URI=https://zf2.learn.os.com/admin/google/photos-auth');
            $this->dispatch('/admin/google/albums?code=11111', 'GET');
            $this->assertResponseStatusCode(302);
            $this->assertModuleName('admin');
            $this->assertControllerName(GoogleController::class);
            $this->assertControllerClass('GoogleController');
            $this->assertMatchedRouteName('admin/google');
            $response = $this->getResponse()->getContent();
            self::assertSame('', $response);
        }
        self::assertTrue(true);
    }

    /**
     * @throws Exception
     */
    public function testGetPhotosActionSuccess()
    {
        $this->HTMLValidate('<!DOCTYPE html>', false);
        if (AbstractMock::$validation) {
            putenv('GOOGLE_CLIENT_ID=1111');
            putenv('GOOGLE_CLIENT_SECRET=1111');
            putenv('GOOGLE_PROJECT_ID=1111');
            putenv('GOOGLE_REDIRECT_URI=https://zf2.learn.os.com/admin/google/photos-auth');
            $this->dispatch('/admin/google/photos?code=11111', 'GET');
            $this->assertResponseStatusCode(302);
            $this->assertModuleName('admin');
            $this->assertControllerName(GoogleController::class);
            $this->assertControllerClass('GoogleController');
            $this->assertMatchedRouteName('admin/google');
            $response = $this->getResponse()->getContent();
            self::assertSame('', $response);
        }
        self::assertTrue(true);
    }

    /**
     * @throws Exception
     */
    public function testGetPhotosAuthActionSuccess()
    {
        putenv('GOOGLE_CLIENT_ID=1111');
        putenv('GOOGLE_CLIENT_SECRET=1111');
        putenv('GOOGLE_PROJECT_ID=1111');
        putenv('GOOGLE_REDIRECT_URI=https://zf2.learn.os.com/admin/google/photos-auth');
        $this->dispatch('/admin/google/photos-auth', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('admin');
        $this->assertControllerName(GoogleController::class);
        $this->assertControllerClass('GoogleController');
        $this->assertMatchedRouteName('admin/google');
        $response = $this->getResponse()->getContent();
        $expected = file_get_contents(
            __DIR__ . '/../../test/data/Controller/Google/GetPhotosAuthActionSuccess.html'
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
