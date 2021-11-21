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

use Admin\Entity\ImageUpload;
use Admin\Service\MediaManagerService;
use Exception;
use MobileTest\AbstractMock;
use Zend\File\Transfer\Adapter\Http;

/**
 * Class MediaManagerServiceTest
 * @package AdminTest\Service
 */
class MediaManagerServiceTest extends AbstractMock
{
    private MediaManagerService $mediaManagerService;

    public function setUp(): void
    {
        parent::setUp();

        $this->mediaManagerService = $this->serviceManager->get('MediaManagerService');
    }

    /**
     * @throws Exception
     */
    public function testGetUserTable()
    {
        $userRepository = $this->serviceManager->get('UserRepository');
        $res = $this->mediaManagerService->getUserTable();
        $this->assertEquals($userRepository, $res);
    }

    /**
     * @throws Exception
     */
    public function testGetUploadTable()
    {
        $uploadRepository = $this->serviceManager->get('UploadRepository');
        $res = $this->mediaManagerService->getUploadTable();
        $this->assertEquals($uploadRepository, $res);
    }

    /**
     * @throws Exception
     */
    public function testGetImageUploadTable()
    {
        $imageUploadRepository = $this->serviceManager->get('ImageUploadRepository');
        $res = $this->mediaManagerService->getImageUploadTable();
        $this->assertEquals($imageUploadRepository, $res);
    }

    /**
     * @throws Exception
     */
    public function testGetWebinoImageThumb()
    {
        $webinoImageThumb = $this->serviceManager->get('WebinoImageThumb');
        $res = $this->mediaManagerService->getWebinoImageThumb();
        $this->assertEquals($webinoImageThumb, $res);
    }

    /**
     * @throws Exception
     */
    public function testImageUpload()
    {
        $res = $this->mediaManagerService->imageUpload('PHP', 'car_001.jpg', 'ivan@gmail.com');
        $this->assertEquals(true, $res);
    }

    /**
     * @throws Exception
     */
    public function testGenerateThumbnail()
    {
        $res = $this->mediaManagerService->generateThumbnail('car_001.jpg');
        $this->assertEquals('tn_car_001.jpg', $res);
    }

    /**
     * @throws Exception
     */
    public function testGetAdapter()
    {
        $config = $this->getConfig();
        $res = $this->mediaManagerService->getAdapter($config['module_config']['image_upload_location']);
        $this->assertEquals(new Http(), $res);
    }

    /**
     * @throws Exception
     */
    public function testGetFile()
    {
        $imageUpload = new ImageUpload();
        $imageUpload->exchangeArray([
            'thumbnail' => 'tn_car_001.jpg'
        ]);
        $res = $this->mediaManagerService->getFile('thumb', $imageUpload);
        $this->assertGreaterThan(1, strlen($res));
    }

    /**
     * @throws Exception
     */
    public function testGetGooglePhotos()
    {
        $res = $this->mediaManagerService->getGooglePhotos();
        $this->assertEquals([], $res);
    }

    /**
     * @throws Exception
     */
    public function testGetYoutubeVideos()
    {
        $res = $this->mediaManagerService->getYoutubeVideos();
        $this->assertEquals([], $res);
    }

    /**
     * @throws Exception
     */
    public function testDelete()
    {
        $res = $this->mediaManagerService->delete(1);
        $this->assertEquals(null, $res);
    }
}
