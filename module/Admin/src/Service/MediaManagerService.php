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

namespace Admin\Service;

use Admin\Entity\ImageUpload;
use Admin\Repository\ImageUploadRepository;
use Admin\Repository\UploadRepository;
use Admin\Repository\UserRepository;
use Exception;
use HttpException;
use WebinoImageThumb\Service\ImageThumb;
use Zend\File\Transfer\Adapter\Http;
use Zend\Http\Client\Adapter\Curl;

/**
 * Class MediaManagerService
 * @package Admin\Service
 */
class MediaManagerService extends AbstractService
{
    const GOOGLE_USER_ID = 'zf2.book@gmail.com';
    const GOOGLE_PASSWORD = 'pa$$w0rd';

    private ImageUploadRepository $imageUploadTable;
    private UserRepository $userTable;
    private UploadRepository $uploadTable;
    private ImageThumb $webinoImageThumb;

    /**
     * MediaManagerService constructor.
     * @param array<array> $config
     * @param UserRepository $userTable
     * @param UploadRepository $uploadTable
     * @param ImageUploadRepository $imageUploadTable
     * @param ImageThumb $webinoImageThumb
     */
    public function __construct(
        array                 $config,
        UserRepository        $userTable,
        UploadRepository      $uploadTable,
        ImageUploadRepository $imageUploadTable,
        ImageThumb            $webinoImageThumb
    ) {
        parent::__construct($config);
        $this->userTable = $userTable;
        $this->uploadTable = $uploadTable;
        $this->imageUploadTable = $imageUploadTable;
        $this->webinoImageThumb = $webinoImageThumb;
    }

    /**
     * @return UserRepository
     */
    public function getUserTable(): UserRepository
    {
        return $this->userTable;
    }

    /**
     * @return UploadRepository
     */
    public function getUploadTable(): UploadRepository
    {
        return $this->uploadTable;
    }

    /**
     * @return ImageUploadRepository
     */
    public function getImageUploadTable(): ImageUploadRepository
    {
        return $this->imageUploadTable;
    }

    /**
     * @return ImageThumb
     */
    public function getWebinoImageThumb(): ImageThumb
    {
        return $this->webinoImageThumb;
    }

    /**
     * @param string $label
     * @param string $filename
     * @param string $user_email
     *
     * @return bool
     */
    public function imageUpload(string $label, string $filename, string $user_email): bool
    {
        $exchange_data = [];
        try {
            $userTable = $this->getUserTable();
            $user = $userTable->getUserByEmail($user_email);
            $exchange_data['user_id'] = $user ? $user->id : 0;
            $exchange_data['label'] = $label;
            $exchange_data['filename'] = $filename;
            $exchange_data['thumbnail'] = $this->generateThumbnail($filename);

            $upload = new ImageUpload();
            $upload->exchangeArray($exchange_data);
            $uploadTable = $this->getImageUploadTable();
            $uploadTable->saveUpload($upload);
            $result = true;
        } catch (Exception $e) {
            $result = false;
        }

        return $result;
    }

    /**
     * GenerateThumbnail
     * http://webino.github.io/WebinoImageThumb/
     *
     * @param string $imageFileName
     *
     * @return string
     */
    public function generateThumbnail(string $imageFileName): string
    {
        $path = $this->getLocationPath('image_upload_location');
        $sourceImageFileName = $path . '/' . $imageFileName;
        $thumbnailFileName = 'tn_' . $imageFileName;

        $imageThumb = $this->getWebinoImageThumb();
        $thumb = $imageThumb->create($sourceImageFileName, $options = []);
        $thumb->resize(75, 75);
        $thumb->save($path . '/' . $thumbnailFileName);

        return $thumbnailFileName;
    }

    /**
     * @param string $uploadPath
     *
     * @return Http
     */
    public function getAdapter(string $uploadPath): Http
    {
        $adapter = new Http();
        $adapter->setDestination($uploadPath);

        return $adapter;
    }

    /**
     * @param int $uploadId
     * @throws Exception
     */
    public function delete(int $uploadId): void
    {
        $uploadTable = $this->getImageUploadTable();
        $upload = $uploadTable->getUpload($uploadId);
        $uploadPath = $this->getLocationPath('image_upload_location');
        // Remove Files
        if (file_exists($uploadPath ."/" . $upload->filename)) {
            unlink($uploadPath ."/" . $upload->filename);
        }
        if (file_exists($uploadPath ."/" . $upload->thumbnail)) {
            unlink($uploadPath ."/" . $upload->thumbnail);
        }
        // Delete Records
        $uploadTable->deleteUpload($uploadId);
    }

    /**
     * @param string $subaction
     * @param ImageUpload $imageUpload
     *
     * @return string
     */
    public function getFile(string $subaction, ImageUpload $imageUpload): string
    {
        $uploadPath = $this->getLocationPath('image_upload_location');
        if ($subaction == 'thumb') {
            $filename = $uploadPath ."/" . $imageUpload->thumbnail;
        } else {
            $filename = $uploadPath ."/" . $imageUpload->filename;
        }
        $contents = false;
        if (file_exists($filename)) {
            $contents = file_get_contents($filename);
        }

        return $contents == false ? '' : $contents;
    }

    /**
     * @return array<array>
     */
    public function getGooglePhotos(): array
    {
        try {
            $gAlbums = [];
            if (class_exists('\ZendGData\HttpClient')
                && class_exists('\ZendGData\ClientLogin')
                && class_exists('\ZendGData\Photos')
            ) {
                $adapter = new Curl();
                $adapter->setOptions([
                    'curloptions' => [
                        CURLOPT_SSL_VERIFYPEER => false,
                    ]
                ]);
                $httpClient = new \ZendGData\HttpClient();
                $httpClient->setAdapter($adapter);
                $client = \ZendGData\ClientLogin::getHttpClient(
                    self::GOOGLE_USER_ID,
                    self::GOOGLE_PASSWORD,
                    \ZendGData\Photos::AUTH_SERVICE_NAME,
                    $httpClient
                );

                $gp = new \ZendGData\Photos($client);

                $userFeed = $gp->getUserFeed(self::GOOGLE_USER_ID);
                foreach ($userFeed as $userEntry) {
                    $albumId = $userEntry->getGphotoId()->getText();
                    $gAlbums[$albumId]['label'] = $userEntry->getTitle()->getText();

                    $query = $gp->newAlbumQuery();
                    $query->setUser(self::GOOGLE_USER_ID);
                    $query->setAlbumId($albumId);

                    $albumFeed = $gp->getAlbumFeed($query);

                    foreach ($albumFeed as $photoEntry) {
                        $photoId = $photoEntry->getGphotoId()->getText();
                        $photoUrl = '';
                        $thumbUrl = '';
                        if ($photoEntry->getMediaGroup()->getContent() != null) {
                            $mediaContentArray = $photoEntry->getMediaGroup()->getContent();
                            $photoUrl = $mediaContentArray[0]->getUrl();
                        }

                        if ($photoEntry->getMediaGroup()->getThumbnail() != null) {
                            $mediaThumbnailArray = $photoEntry->getMediaGroup()->getThumbnail();
                            $thumbUrl = $mediaThumbnailArray[0]->getUrl();
                        }

                        $albumPhoto = [];
                        $albumPhoto['id'] = $photoId;
                        $albumPhoto['photoUrl'] = $photoUrl;
                        $albumPhoto['thumbUrl'] = $thumbUrl;

                        $gAlbums[$albumId]['photos'][] = $albumPhoto;
                    }
                }
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage() . "<br />\n";
        }

        return $gAlbums;
    }

    /**
     * @return array<array>
     */
    public function getYoutubeVideos(): array
    {
        try {
            $yVideos = [];
            if (class_exists('\ZendGData\HttpClient')
                && class_exists('\ZendGData\ClientLogin')
                && class_exists('\ZendGData\YouTube')
            ) {
                $adapter = new Curl();
                $adapter->setOptions([
                    'curloptions' => [
                        CURLOPT_SSL_VERIFYPEER => false,
                    ]
                ]);

                $httpClient = new \ZendGData\HttpClient();
                $httpClient->setAdapter($adapter);

                $client = \ZendGData\ClientLogin::getHttpClient(
                    self::GOOGLE_USER_ID,
                    self::GOOGLE_PASSWORD,
                    \ZendGData\YouTube::AUTH_SERVICE_NAME,
                    $httpClient
                );

                $yt = new \ZendGData\YouTube($client);
                $yt->setMajorProtocolVersion(2);
                $query = $yt->newVideoQuery();
                $query->setOrderBy('relevance');
                $query->setSafeSearch('none');
                $query->setVideoQuery('Zend Framework');

                // Note that we need to pass the version number to the query URL function
                // to ensure backward compatibility with version 1 of the API.
                $videoFeed = $yt->getVideoFeed($query->getQueryUrl(2));
                foreach ($videoFeed as $videoEntry) {
                    $yVideo = [];
                    $yVideo['videoTitle'] = $videoEntry->getVideoTitle();
                    $yVideo['videoDescription'] = $videoEntry->getVideoDescription();
                    $yVideo['watchPage'] = $videoEntry->getVideoWatchPageUrl();
                    $yVideo['duration'] = $videoEntry->getVideoDuration();
                    $videoThumbnails = $videoEntry->getVideoThumbnails();
                    $yVideo['thumbnailUrl'] = $videoThumbnails[0]['url'];
                    $yVideos[] = $yVideo;
                }
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage() . "<br />\n";
        }

        return $yVideos;
    }
}
