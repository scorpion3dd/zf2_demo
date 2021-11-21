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

namespace AdminTest\Repository;

use Admin\Entity\Upload;
use Admin\Repository\UploadRepository;
use Exception;
use MobileTest\AbstractMock;
use Zend\Db\ResultSet\ResultSet;

/**
 * Class UploadRepositoryTest
 * @package AdminTest\Repository
 */
class UploadRepositoryTest extends AbstractMock
{
    private UploadRepository $uploadRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->uploadRepository = $this->serviceManager->get('UploadRepository');
    }

    /**
     * @throws Exception
     */
    public function testSaveUpload()
    {
        $upload = new Upload();
        $upload->exchangeArray([
            'id' => '30',
            'filename' => 'php.png',
            'label' => 'php',
            'user_id' => 1,
        ]);
        $res = $this->uploadRepository->saveUpload($upload);
        $this->assertEquals(null, $res);
    }

    /**
     * @throws Exception
     */
    public function testGetUpload()
    {
        $upload = new Upload();
        $upload->exchangeArray([
            'id' => '1',
            'filename' => 'php.jpg',
            'label' => 'PHP',
            'user_id' => 1,
        ]);
        $res = $this->uploadRepository->getUpload(1);
        $this->assertEquals($upload, $res);
    }

    /**
     * @throws Exception
     */
    public function testDeleteUpload()
    {
        $res = $this->uploadRepository->deleteUpload(100);
        $this->assertEquals(null, $res);
    }

    /**
     * @throws Exception
     */
    public function testDeleteUploadByUser()
    {
        $res = $this->uploadRepository->deleteUploadByUser(100);
        $this->assertEquals(null, $res);
    }

    /**
     * @throws Exception
     */
    public function testDeleteSharedUploadByUpload()
    {
        $res = $this->uploadRepository->deleteSharedUploadByUpload(100);
        $this->assertEquals(null, $res);
    }

    /**
     * @throws Exception
     */
    public function testDeleteSharedUploadByUser()
    {
        $res = $this->uploadRepository->deleteSharedUploadByUser(100);
        $this->assertEquals(null, $res);
    }

    /**
     * @throws Exception
     */
    public function testGetUploadsByUserId()
    {
        $res = $this->uploadRepository->getUploadsByUserId(1);
        $this->assertInstanceOf(ResultSet::class, $res);
    }

    /**
     * @throws Exception
     */
    public function testGetSharedUploadsForUserId()
    {
        $res = $this->uploadRepository->getSharedUploadsForUserId(1);
        $this->assertInstanceOf(ResultSet::class, $res);
    }

    /**
     * @throws Exception
     */
    public function testGetSharedUsers()
    {
        $res = $this->uploadRepository->getSharedUsers(1);
        $this->assertInstanceOf(ResultSet::class, $res);
    }

    /**
     * @throws Exception
     */
    public function testDeleteSharedUsers()
    {
        $res = $this->uploadRepository->deleteSharedUsers(100);
        $this->assertEquals(null, $res);
    }

    /**
     * @throws Exception
     */
    public function testAddSharing()
    {
        $res = $this->uploadRepository->addSharing(100, 200);
        $this->assertEquals(null, $res);
    }

    /**
     * @throws Exception
     */
    public function testRemoveSharing()
    {
        $res = $this->uploadRepository->removeSharing(101, 201);
        $this->assertEquals(null, $res);
    }
}
