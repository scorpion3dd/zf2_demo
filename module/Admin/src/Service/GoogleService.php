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

use Google\ApiCore\ApiException;
use Google\ApiCore\ValidationException;
use Google\Auth\Credentials\UserRefreshCredentials;
use Google\Auth\OAuth2;
use Google\Photos\Library\V1\NewMediaItem;
use Google\Photos\Library\V1\NewMediaItemResult;
use Google\Photos\Library\V1\PhotosLibraryClient;
use Google\Photos\Library\V1\PhotosLibraryResourceFactory;
use Google\Photos\Types\Album;
use Google\Photos\Types\MediaItem;
use GuzzleHttp\Exception\GuzzleException;
use function _PHPStan_76800bfb5\RingCentral\Psr7\str;

/**
 * Class GoogleService
 * @package Admin\Service
 */
class GoogleService extends AbstractService
{
    const SESSION_CREDENTIALS = 'zf2.google.credentials';
    const SERVICE_BOOKS_FILTER = 'free-ebooks';

    private UserRefreshCredentials $credentials;

    /**
     * GoogleService constructor.
     * @param array<array> $config
     */
    public function __construct(array $config)
    {
        parent::__construct($config);
    }

    /**
     * @param string $albumId
     *
     * @return Album
     * @throws ApiException
     * @throws ValidationException
     */
    public function getAlbum(string $albumId): Album
    {
        $photosLibraryClient = new PhotosLibraryClient(['credentials' => $this->getCredentials()]);

        return $photosLibraryClient->getAlbum($albumId);
    }

    /**
     * https://github.com/google/php-photoslibrary/blob/samples/src/albums/index.php
     * https://github.com/google/php-photoslibrary/blob/samples/src/albums/photo.php
     * https://developers.google.com/photos/library/reference/rest/v1/albums
     *
     * @param string $albumId
     *
     * @return array<array>
     * @throws ApiException
     * @throws ValidationException
     */
    public function getPhotos(string $albumId): array
    {
        $items = [];
        $photosLibraryClient = new PhotosLibraryClient(['credentials' => $this->getCredentials()]);
        $album = $photosLibraryClient->getAlbum($albumId);
        $photos = $photosLibraryClient->searchMediaItems(['albumId' => $album->getId()]);
        /** @var MediaItem $mediaItem */
        foreach ($photos->iterateAllElements() as $mediaItem) {
            $items[] = [
                'id' => $mediaItem->getId(),
                'filename' => $mediaItem->getFilename(),
                'product_url' => $mediaItem->getProductUrl(),
                'description' => $mediaItem->getDescription(),
                'base_url' => $mediaItem->getBaseUrl(),
                'date' => $mediaItem->getMediaMetadata()->getCreationTime()->toDateTime()->format('j F Y, H:i:s'),
            ];
        }

        return $items;
    }

    /**
     * @param string $name
     *
     * @return array<array>
     */
    private function getClientSecretJson(string $name = 'google_keys'): array
    {
        $json = (string)file_get_contents($this->getLocationPath($name));
        $clientSecretJson = json_decode($json, true)['web'];
        $clientSecretJson['client_id'] = getenv('GOOGLE_CLIENT_ID');
        $clientSecretJson['client_secret'] = getenv('GOOGLE_CLIENT_SECRET');
        $clientSecretJson['project_id'] = getenv('GOOGLE_PROJECT_ID');
        $clientSecretJson['redirect_uris'][0] = getenv('GOOGLE_REDIRECT_URI');

        return $clientSecretJson;
    }

    /**
     * https://github.com/google/php-photoslibrary/blob/samples/src/common/common.php
     * https://console.cloud.google.com/apis/credentials
     * https://github.com/googleapis/google-api-php-client
     * https://github.com/google/php-photoslibrary
     *
     * @param string|null $code
     */
    public function getAuth(?string $code = ''): void
    {
        try {
            $clientSecretJson = $this->getClientSecretJson();
            $scopes = ['https://www.googleapis.com/auth/photoslibrary'];
            $oauth2 = new OAuth2([
                'clientId' => $clientSecretJson['client_id'],
                'clientSecret' => $clientSecretJson['client_secret'],
                'authorizationUri' => 'https://accounts.google.com/o/oauth2/v2/auth',
                'redirectUri' => $clientSecretJson['redirect_uris'][0],
                'tokenCredentialUri' => 'https://www.googleapis.com/oauth2/v4/token',
                'scope' => $scopes
            ]);
            if (empty($code)) {
                $authenticationUrl = $oauth2->buildFullAuthorizationUri(['access_type' => 'offline']);
                if (getenv('APPLICATION_ENV') != 'TEST') {
                    header("Location: " . $authenticationUrl);
                    exit();
                }
            } else {
                $oauth2->setCode($code);
                $authToken = $oauth2->fetchAuthToken();
                $credentials = new UserRefreshCredentials(
                    $scopes,
                    [
                        'client_id' => $clientSecretJson['client_id'],
                        'client_secret' => $clientSecretJson['client_secret'],
                        'refresh_token' => $authToken['access_token']
                    ]
                );
                if (isset($_SESSION)) {
                    $_SESSION[self::SESSION_CREDENTIALS] = $credentials;
                }
            }
        } catch (ApiException $exception) {
            echo $exception;
        } catch (ValidationException $exception) {
            echo $exception;
        }
    }

    /**
     * https://developers.google.com/photos/library/guides/upload-media#php
     *
     * @param string $localFilePath
     * @param string $fileName
     * @param string $mimeType
     *
     * @return string
     * @throws ValidationException
     * @throws GuzzleException
     */
    public function uploadMedia(
        string $localFilePath,
        string $fileName,
        string $mimeType = 'image/png'
    ): string {
        $uploadToken = '';
        $photosLibraryClient = new PhotosLibraryClient(['credentials' => $this->getCredentials()]);
        $fullFilePath = $localFilePath . '/' . $fileName;
        if (file_exists($fullFilePath)) {
            $fileContents = (string)file_get_contents($fullFilePath);
            $uploadToken = $photosLibraryClient->upload($fileContents, $fileName, $mimeType);
        }

        return $uploadToken;
    }
    /**
     * https://developers.google.com/photos/library/reference/rest/v1/albums
     *
     * @param string $name
     *
     * @return string
     * @throws ApiException
     * @throws ValidationException
     */
    public function createAlbum(string $name): string
    {
        $newAlbum = PhotosLibraryResourceFactory::album($name);
        $photosLibraryClient = new PhotosLibraryClient(['credentials' => $this->getCredentials()]);
        $createdAlbum = $photosLibraryClient->createAlbum($newAlbum);

        return $createdAlbum->getId();
    }

    /**
     * https://google.github.io/php-photoslibrary/v1.6.2/classes/Google-Photos-Library-V1-Gapic-PhotosLibraryGapicClient.html#method_batchCreateMediaItems
     * https://developers.google.com/photos/library/guides/upload-media#php
     *
     * @param NewMediaItem[] $newMediaItems
     * @param string $albumId
     *
     * @return NewMediaItemResult|bool
     * @throws ApiException
     * @throws ValidationException
     */
    public function createMediaItems(array $newMediaItems, string $albumId)
    {
        $photosLibraryClient = new PhotosLibraryClient(['credentials' => $this->getCredentials()]);
        $response = $photosLibraryClient->batchCreateMediaItems($newMediaItems, ['albumId' => $albumId]);
        /** @var NewMediaItemResult $itemResult */
        foreach ($response->getNewMediaItemResults() as $itemResult) {
            $status = $itemResult->getStatus();
            if ($status->getMessage() == 'Success') {
                return $itemResult;
            }
        }

        return false;
    }

    /**
     * https://github.com/google/php-photoslibrary/blob/samples/src/albums/index.php
     * https://developers.google.com/photos/library/reference/rest/v1/albums
     *
     * @return array<array>
     * @throws ApiException
     * @throws ValidationException
     */
    public function getAlbums(): array
    {
        $items = [];
        $photosLibraryClient = new PhotosLibraryClient(['credentials' => $this->getCredentials()]);
        $albums = $photosLibraryClient->listAlbums();
        /** @var Album $album */
        foreach ($albums->iterateAllElements() as $album) {
            $items[] = [
                'id' => $album->getId(),
                'title' => $album->getTitle(),
                'photo_base_url' => $album->getCoverPhotoBaseUrl(),
                'product_url' => $album->getProductUrl(),
                'share_info' => $album->getShareInfo(),
            ];
        }

        return $items;
    }

    /**
     * @param string $words
     *
     * @return array<array>
     */
    public function getBooks(string $words): array
    {
        $items = [];
        $client = new \Google\Client();
        $client->setApplicationName("Client_Library_Examples");
        $client->setDeveloperKey((string)getenv('GOOGLE_DEVELOPER_KEY'));
        try {
            $service = new \Google_Service_Books($client);
            $optParams = ['filter' => self::SERVICE_BOOKS_FILTER];
            $results = $service->volumes->listVolumes($words, $optParams);
            foreach ($results->getItems() as $item) {
                $items[] = $item;
            }
        } catch (\Exception $ex) {
            $caughtException = $ex;
        }

        return $items;
    }

    /**
     * @return bool
     */
    public function isSessionCred(): bool
    {
        return isset($_SESSION[self::SESSION_CREDENTIALS]) ? true : false;
    }

    /**
     * @return UserRefreshCredentials|null
     */
    public function getSessionCred(): ?UserRefreshCredentials
    {
        return isset($_SESSION[self::SESSION_CREDENTIALS]) ? $_SESSION[self::SESSION_CREDENTIALS] : null;
    }

    public function deleteSessionCred(): void
    {
        if (isset($_SESSION)) {
            unset($_SESSION[self::SESSION_CREDENTIALS]);
        }
    }

    /**
     * @return UserRefreshCredentials|null
     */
    public function getCredentials(): ?UserRefreshCredentials
    {
        return $this->credentials;
    }

    /**
     * @param UserRefreshCredentials|null $credentials
     */
    public function setCredentials(?UserRefreshCredentials $credentials): void
    {
        $this->credentials = $credentials;
        if (isset($_SESSION)) {
            $_SESSION[self::SESSION_CREDENTIALS] = $credentials;
        }
    }
}
