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

use Admin\Service\UploadManagerService;
use Exception;
use MobileTest\AbstractMock;

/**
 * Class UploadManagerServiceTest
 * @package AdminTest\Service
 */
class UploadManagerServiceTest extends AbstractMock
{
    private UploadManagerService $uploadManagerService;

    public function setUp(): void
    {
        parent::setUp();

        $this->uploadManagerService = $this->serviceManager->get('UploadManagerService');
    }

    /**
     * @throws Exception
     */
    public function testGetUploadTable()
    {
        $uploadRepository = $this->serviceManager->get('UploadRepository');
        $res = $this->uploadManagerService->getUploadTable();
        $this->assertEquals($uploadRepository, $res);
    }

    /**
     * @throws Exception
     */
    public function testGetUserTable()
    {
        $userRepository = $this->serviceManager->get('UserRepository');
        $res = $this->uploadManagerService->getUserTable();
        $this->assertEquals($userRepository, $res);
    }

    /**
     * @throws Exception
     */
    public function testGetFileUploadLocation()
    {
        $res = $this->uploadManagerService->getFileUploadLocation();
        $this->assertStringContainsString('/../test/data/Service/MediaManager', $res);
    }

    /**
     * @throws Exception
     */
    public function testGetSharedUploads()
    {
        $res = $this->uploadManagerService->getSharedUploads(1);
        $this->assertSame([
            1 => [
                'label' => 'PHP',
                'owner' => 'admin',
            ],
            2 => [
                'label' => 'Node JS',
                'owner' => 'admin6',
            ]
        ], $res);
    }

    /**
     * @throws Exception
     */
    public function testUpload()
    {
        $res = $this->uploadManagerService->upload('PHP', 'car_001.jpg', 'ivam@gmail.com');
        $this->assertSame(null, $res);
    }

    /**
     * @throws Exception
     */
    public function testGetSharedUsers()
    {
        $res = $this->uploadManagerService->getSharedUsers(1);
        $this->assertSame([1 => 'admin'], $res);
    }

    /**
     * @throws Exception
     */
    public function testPrepearResponse()
    {
        $res = $this->uploadManagerService->prepearResponse(1, $this->getResponse());
        $this->assertSame(200, $res->getStatusCode());
        $this->assertSame(false, $res->getContent());
        $this->assertSame('', $res->getBody());
    }

    /**
     * @throws Exception
     */
    public function testDelete()
    {
        $res = $this->uploadManagerService->delete(1);
        $this->assertSame(null, $res);
    }
}
