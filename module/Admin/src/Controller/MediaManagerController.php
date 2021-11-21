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

use Admin\Form\ImageUploadForm;
use Admin\Service\MediaManagerService;
use Exception;
use Zend\Authentication\AuthenticationService;
use Zend\Http\Headers;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Log\Logger;
use Zend\Stdlib\ResponseInterface;
use Zend\View\Model\ViewModel;

/**
 * Class MediaManagerController
 * @package Admin\Controller
 */
class MediaManagerController extends ActionController
{
    /** @var MediaManagerService $mediaManagerService */
    private MediaManagerService $mediaManagerService;
    /** @var ImageUploadForm<array> $imageUploadForm */
    private ImageUploadForm $imageUploadForm;

    /**
     * MediaManagerController constructor.
     *
     * @param Logger $logger
     * @param AuthenticationService $authService
     * @param ImageUploadForm<array> $imageUploadForm
     * @param MediaManagerService $mediaManagerService
     */
    public function __construct(
        Logger $logger,
        AuthenticationService $authService,
        ImageUploadForm $imageUploadForm,
        MediaManagerService $mediaManagerService
    ) {
        parent::__construct($authService, $logger);
        $this->mediaManagerService = $mediaManagerService;
        $this->imageUploadForm = $imageUploadForm;
    }

    /**
     * @return ViewModel<array>|Response
     * @throws Exception
     */
    public function indexAction()
    {
        $uploadTable = $this->getMediaManagerService()->getImageUploadTable();
        $userTable = $this->getMediaManagerService()->getUserTable();
        $userEmail = $this->getAuthService()->getStorage()->read();
        $user = $userTable->getUserByEmail($userEmail);

        return new ViewModel(
            [
                'myUploads' => $uploadTable->getUploadsByUserId(isset($user) ? (int)$user->id : 0),
                'googleAlbums' => $this->getMediaManagerService()->getGooglePhotos(),
                'youtubeVideos' => $this->getMediaManagerService()->getYoutubeVideos(),
            ]
        );
    }

    /**
     * @return array<string, ImageUploadForm<array>>|Response
     * @throws Exception
     */
    public function processUploadAction()
    {
        $form = $this->getImageUploadForm();
        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $uploadPath = $this->getMediaManagerService()->getLocationPath('image_upload_location');
                if (! is_dir($uploadPath) || ! file_exists($uploadPath)) {
                    throw new Exception('Upload Path does not exist');
                }
                $adapter = $this->getMediaManagerService()->getAdapter($uploadPath);
                $filename = basename(strval($adapter->getFileName()));
                if ($adapter->receive($filename)) {
                    $user_email = $this->getAuthService()->getStorage()->read();
                    if ($this->getMediaManagerService()->imageUpload(
                        $request->getPost()->get('label'),
                        $filename,
                        $user_email
                    )) {
                        return $this->redirect()->toRoute('admin/media');
                    }
                }
            }
        }

        return ['form' => $form];
    }

    /**
     * @return ViewModel<array>
     */
    public function uploadAction(): ViewModel
    {
        return new ViewModel(['form' => $this->getImageUploadForm()]);
    }

    /**
     * @return Response
     * @throws Exception
     */
    public function deleteAction(): Response
    {
        $this->getMediaManagerService()->delete((int)$this->params()->fromRoute('id'));

        return $this->redirect()->toRoute('admin/media');
    }

    /**
     * @return ViewModel<array>
     * @throws Exception
     */
    public function viewAction(): ViewModel
    {
        $imageUploadTable = $this->getMediaManagerService()->getImageUploadTable();
        $imageUpload = $imageUploadTable->getUpload((int)$this->params()->fromRoute('id'));

        return new ViewModel(['upload' => $imageUpload]);
    }

    /**
     * @return ResponseInterface
     * @throws Exception
     */
    public function showImageAction(): ResponseInterface
    {
        $imageUploadTable = $this->getMediaManagerService()->getImageUploadTable();
        $imageUpload = $imageUploadTable->getUpload((int)$this->params()->fromRoute('id'));
        /** @var Response $response */
        $response = $this->getEvent()->getResponse();
        /** @var Headers $headers */
        $headers = $response->getHeaders();
        $headers->addHeaders([
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment;filename="' . $imageUpload->filename . '"',

        ]);
        $response->setContent($this->getMediaManagerService()
            ->getFile($this->params()->fromRoute('subaction'), $imageUpload));

        return $response;
    }

    /**
     * @return ImageUploadForm<array>
     */
    private function getImageUploadForm(): ImageUploadForm
    {
        return $this->imageUploadForm;
    }

    /**
     * @return MediaManagerService
     */
    private function getMediaManagerService(): MediaManagerService
    {
        return $this->mediaManagerService;
    }
}
