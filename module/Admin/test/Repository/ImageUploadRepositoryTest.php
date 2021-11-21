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

use Admin\Entity\ImageUpload;
use Admin\Repository\ImageUploadRepository;
use Exception;
use MobileTest\AbstractMock;
use Zend\Db\ResultSet\ResultSet;

/**
 * Class ImageUploadRepositoryTest
 * @package AdminTest\Repository
 */
class ImageUploadRepositoryTest extends AbstractMock
{
    private ImageUploadRepository $imageUploadRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->imageUploadRepository = $this->serviceManager->get('ImageUploadRepository');
    }

    /**
     * @throws Exception
     */
    public function testSaveUpload()
    {
        $imageUpload = new ImageUpload();
        $imageUpload->exchangeArray([
            'id' => 1,
            'filename' => 'file name',
            'thumbnail' => 'thumb nail',
            'label' => 'label',
            'user_id' => 1,
        ]);
        $res = $this->imageUploadRepository->saveUpload($imageUpload);
        $this->assertEquals(null, $res);
    }

    /**
     * @throws Exception
     */
    public function testGetUpload()
    {
        $res = $this->imageUploadRepository->getUpload(1);
        $this->assertInstanceOf(ImageUpload::class, $res);
    }

    /**
     * @throws Exception
     */
    public function testGetUploadsByUserId()
    {
        $res = $this->imageUploadRepository->getUploadsByUserId(1);
        $this->assertInstanceOf(ResultSet::class, $res);
    }

    /**
     * @throws Exception
     */
    public function testDeleteUpload()
    {
        $res = $this->imageUploadRepository->deleteUpload(100);
        $this->assertEquals(null, $res);
    }
}
