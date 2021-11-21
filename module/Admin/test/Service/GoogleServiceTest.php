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

namespace AdminTest\Service;

use Admin\Service\GoogleService;
use Exception;
use Google\Auth\Credentials\UserRefreshCredentials;
use MobileTest\AbstractMock;

/**
 * Class GoogleServiceTest
 * @package AdminTest\Service
 */
class GoogleServiceTest extends AbstractMock
{
    private GoogleService $googleService;

    public function setUp(): void
    {
        parent::setUp();

        $this->googleService = $this->serviceManager->get('GoogleService');
    }

    /**
     * @throws Exception
     */
    public function testGetAlbum()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->googleService->setCredentials(new UserRefreshCredentials('', ''));
        $this->googleService->getAlbum('123');
    }

    /**
     * @throws Exception
     */
    public function testGetPhotos()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->googleService->setCredentials(new UserRefreshCredentials('', ''));
        $this->googleService->getPhotos('123');
    }

    /**
     * @throws Exception
     */
    public function testGetAuth()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->googleService->setCredentials(new UserRefreshCredentials('', ''));
        $this->googleService->getAuth();
    }

    /**
     * @throws Exception
     */
    public function testUploadMedia()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->googleService->setCredentials(new UserRefreshCredentials('', ''));
        $this->googleService->uploadMedia('path', 'filename');
    }

    /**
     * @throws Exception
     */
    public function testCreateAlbum()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->googleService->setCredentials(new UserRefreshCredentials('', ''));
        $this->googleService->createAlbum('name');
    }

    /**
     * @throws Exception
     */
    public function testCreateMediaItems()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->googleService->setCredentials(new UserRefreshCredentials('', ''));
        $this->googleService->createMediaItems([], 'uid');
    }

    /**
     * @throws Exception
     */
    public function testGetAlbums()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->googleService->setCredentials(new UserRefreshCredentials('', ''));
        $this->googleService->getAlbums();
    }

    /**
     * @throws Exception
     */
    public function testGetBooks()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->googleService->setCredentials(new UserRefreshCredentials('', ''));
        $this->googleService->getBooks('php');
    }

    /**
     * @throws Exception
     */
    public function testIsSessionCred()
    {
        $is = $this->googleService->isSessionCred();
        $this->assertSame(false, $is);
    }

    /**
     * @throws Exception
     */
    public function testGetSessionCred()
    {
        $this->assertSame(null, $this->googleService->getSessionCred());
    }

    /**
     * @throws Exception
     */
    public function testDeleteSessionCred()
    {
        $this->assertSame(null, $this->googleService->deleteSessionCred());
    }

    /**
     * @throws Exception
     */
    public function testGetCredentials()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->googleService->setCredentials(new UserRefreshCredentials('', ''));
        $this->assertSame(null, $this->googleService->getCredentials());
    }

    /**
     * @throws Exception
     */
    public function testSetCredentials()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->googleService->setCredentials(new UserRefreshCredentials('', ''));
    }
}
