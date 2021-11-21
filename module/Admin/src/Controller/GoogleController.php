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

namespace Admin\Controller;

use Admin\Form\GoogleNewAlbumForm;
use Admin\Form\GoogleSearchForm;
use Admin\Form\GoogleUploadPhotoForm;
use Admin\Repository\ImageUploadRepository;
use Admin\Service\GoogleService;
use Google\Photos\Library\V1\PhotosLibraryResourceFactory;
use Google\Photos\Types\MediaItem;
use Google\Photos\Library\V1\NewMediaItemResult;
use GuzzleHttp\Exception\GuzzleException;
use Zend\Authentication\AuthenticationService;
use Zend\File\Transfer\Adapter\Http;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Log\Logger;
use Zend\View\Model\ViewModel;
use Google\ApiCore\ApiException;
use Google\ApiCore\ValidationException;
use GuzzleHttp\Exception\RequestException;

/**
 * Class GoogleController
 * @package Admin\Controller
 */
class GoogleController extends ActionController
{
    /** @var GoogleService $googleService */
    private GoogleService $googleService;
    /** @var ImageUploadRepository $imageUploadTable */
    private ImageUploadRepository $imageUploadTable;
    /** @var GoogleNewAlbumForm<array> $googleNewAlbumForm */
    private GoogleNewAlbumForm $googleNewAlbumForm;
    /** @var GoogleUploadPhotoForm<array> $googleUploadPhotoForm */
    private GoogleUploadPhotoForm $googleUploadPhotoForm;
    /** @var GoogleSearchForm<array> $googleSearchForm */
    private GoogleSearchForm $googleSearchForm;

    /**
     * GoogleController constructor.
     *
     * @param Logger $logger
     * @param AuthenticationService $authService
     * @param ImageUploadRepository $imageUploadTable
     * @param GoogleNewAlbumForm<array> $googleNewAlbumForm
     * @param GoogleUploadPhotoForm<array> $googleUploadPhotoForm
     * @param GoogleSearchForm<array> $googleSearchForm
     * @param GoogleService $googleService
     */
    public function __construct(
        Logger                $logger,
        AuthenticationService $authService,
        ImageUploadRepository $imageUploadTable,
        GoogleNewAlbumForm    $googleNewAlbumForm,
        GoogleUploadPhotoForm $googleUploadPhotoForm,
        GoogleSearchForm      $googleSearchForm,
        GoogleService         $googleService
    ) {
        if (session_status() != PHP_SESSION_ACTIVE && ! headers_sent()) {
            session_start();
        }
        parent::__construct($authService, $logger);
        $this->imageUploadTable = $imageUploadTable;
        $this->googleNewAlbumForm = $googleNewAlbumForm;
        $this->googleUploadPhotoForm = $googleUploadPhotoForm;
        $this->googleSearchForm = $googleSearchForm;
        $this->googleService = $googleService;
        if ($this->getGoogleService()->isSessionCred()) {
            $this->getGoogleService()->setCredentials($this->getGoogleService()->getSessionCred());
        }
    }

    /**
     * @return ViewModel<array>
     */
    public function indexAction(): ViewModel
    {
        return new ViewModel();
    }

    /**
     * @return Response|ViewModel<array>
     * @throws ApiException
     * @throws GuzzleException
     * @throws ValidationException
     */
    public function uploadPhotoAction()
    {
        $albumId = $this->params()->fromRoute('albumId');
        $form = $this->getGoogleUploadPhotoForm();
        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = $request->getPost();
            $form->setData($post);
            $albumId = $albumId ? $albumId : $post->get('albumId');
            if ($form->isValid() && $albumId) {
                $uploadFile = $this->params()->fromFiles('fileupload');
                $uploadPath = $this->getGoogleService()->getLocationPath('image_upload_location');
                if (! is_dir($uploadPath) || ! file_exists($uploadPath)) {
                    throw new \Exception('Upload Path does not exist');
                }

                $adapter = new Http();
                $adapter->setDestination($uploadPath);
                $filename = basename(strval($adapter->getFileName()));
                if ($adapter->receive($filename)) {
                    if ($this->getGoogleService()->isSessionCred()) {
                        $uploadToken = $this->getGoogleService()->uploadMedia(
                            $uploadPath,
                            $filename,
                            $uploadFile['type']
                        );
                        if ($uploadToken) {
                            $name = $post->get('name');
                            $description = $post->get('description');
                            $newMediaItems = [];
                            $newMediaItems[] = PhotosLibraryResourceFactory::newMediaItemWithDescriptionAndFileName(
                                $uploadToken,
                                $description,
                                $name
                            );
                            /** @var NewMediaItemResult $itemResult */
                            $itemResult = $this->getGoogleService()->createMediaItems($newMediaItems, $albumId);
                            /** @var MediaItem $mediaItem */
                            $mediaItem = $itemResult->getMediaItem();
                            $album = $this->getGoogleService()->getAlbum($albumId);

                            return $this->redirect()->toRoute(
                                'admin/google',
                                ['action' => 'confirm-upload-photo'],
                                ['query' => [
                                    'albumId' => $albumId,
                                    'albumTitle' => $album->getTitle(),
                                    'productId' => $mediaItem->getId(),
                                    'productUrl' => $mediaItem->getProductUrl(),
                                    'productName' => $name,
                                    'fileName' => $uploadFile['name']
                                ]]
                            );
                        }
                    } else {
                        $this->getGoogleService()->getAuth($this->params()->fromQuery('code'));
                    }
                }
            }
        }
        $albumTitle = '';
        if ($albumId && $this->getGoogleService()->isSessionCred()) {
            $album = $this->getGoogleService()->getAlbum($albumId);
            $albumTitle = $album->getTitle();
        }

        return new ViewModel([
            'form' => $form,
            'albumId' => $albumId,
            'albumTitle' => $albumTitle,
        ]);
    }

    /**
     * @return ViewModel<array>
     */
    public function confirmUploadPhotoAction(): ViewModel
    {
        return new ViewModel([
            'albumId' => $this->params()->fromQuery('albumId'),
            'albumTitle' => $this->params()->fromQuery('albumTitle'),
            'fileName' => $this->params()->fromQuery('fileName'),
            'productId' => $this->params()->fromQuery('productId'),
            'productUrl' => $this->params()->fromQuery('productUrl'),
            'productName' => $this->params()->fromQuery('productName'),
        ]);
    }

    /**
     * @return Response|ViewModel<array>
     * @throws ApiException
     * @throws ValidationException
     */
    public function newAlbumAction()
    {
        $form = $this->getGoogleNewAlbumForm();
        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = $request->getPost();
            $form->setData($post);
            if ($form->isValid()) {
                $name = $post->get('name');
                if ($this->getGoogleService()->isSessionCred()) {
                    $albumId = $this->getGoogleService()->createAlbum($name);

                    return $this->redirect()->toRoute(
                        'admin/google',
                        ['action' => 'confirm-add-album'],
                        ['query' => ['albumId' => $albumId, 'name' => $name]]
                    );
                } else {
                    $code = $this->params()->fromQuery('code');
                    $this->getGoogleService()->getAuth($code);
                }
            }
        }

        return new ViewModel(['form' => $form]);
    }

    /**
     * @return ViewModel<array>
     */
    public function confirmAddAlbumAction(): ViewModel
    {
        return new ViewModel([
            'name' => $this->params()->fromQuery('name'),
            'albumId' => $this->params()->fromQuery('albumId')
        ]);
    }

    /**
     * @return ViewModel<array>
     */
    public function booksAction(): ViewModel
    {
        $books = [];
        $words = 'PHP';
        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $words = $request->getPost()->get('words');
            $books = $this->getGoogleService()->getBooks($words);
        }
        $form = $this->getGoogleSearchForm();
        $form->get('words')->setValue($words);

        return new ViewModel(
            [
                'books' => $books,
                'form' => $form,
            ]
        );
    }

    /**
     * @return Response|ViewModel<array>
     * @throws ApiException
     * @throws ValidationException
     */
    public function albumsAction()
    {
        try {
            $albums = [];
            if ($this->getGoogleService()->isSessionCred()) {
                $albums = $this->getGoogleService()->getAlbums();
            } else {
                $code = $this->params()->fromQuery('code');
                $this->getGoogleService()->getAuth($code);
            }

            return new ViewModel(['albums' => $albums]);
        } catch (RequestException $exception) {
            $this->getGoogleService()->deleteSessionCred();

            return $this->redirect()->toRoute('admin/google', ['action' => 'albums']);
        }
    }

    /**
     * @return Response|ViewModel<array>
     * @throws ApiException
     * @throws ValidationException
     */
    public function photosAction()
    {
        try {
            $photos = [];
            if ($this->getGoogleService()->isSessionCred()) {
                $albumId = $this->params()->fromRoute('albumId');
                $album = $this->getGoogleService()->getAlbum($albumId);
                $photos = $this->getGoogleService()->getPhotos($albumId);
            } else {
                $albumId = 0;
                $album = null;
                $code = $this->params()->fromQuery('code');
                $this->getGoogleService()->getAuth($code);
            }

            return new ViewModel(
                [
                    'photos' => $photos,
                    'albumTitle' => $album->getTitle(),
                    'albumId' => $albumId,
                ]
            );
        } catch (RequestException $exception) {
            $this->getGoogleService()->deleteSessionCred();

            return $this->redirect()->toRoute('admin/google', ['action' => 'photos']);
        }
    }

    /**
     * @return Response|null
     */
    public function photosAuthAction(): ?Response
    {
        $code = $this->params()->fromQuery('code');
        $this->getGoogleService()->getAuth($code);
        if ($this->getGoogleService()->isSessionCred()) {
            return $this->redirect()->toRoute('admin/google', ['action' => 'albums']);
        }

        return null;
    }

    /**
     * @return GoogleNewAlbumForm<array>
     */
    private function getGoogleNewAlbumForm(): GoogleNewAlbumForm
    {
        return $this->googleNewAlbumForm;
    }

    /**
     * @return GoogleUploadPhotoForm<array>
     */
    private function getGoogleUploadPhotoForm(): GoogleUploadPhotoForm
    {
        return $this->googleUploadPhotoForm;
    }

    /**
     * @return ImageUploadRepository
     */
    private function getImageUploadTable(): ImageUploadRepository
    {
        return $this->imageUploadTable;
    }

    /**
     * @return GoogleSearchForm<array>
     */
    private function getGoogleSearchForm(): GoogleSearchForm
    {
        return $this->googleSearchForm;
    }

    /**
     * @return GoogleService
     */
    private function getGoogleService(): GoogleService
    {
        return $this->googleService;
    }
}
