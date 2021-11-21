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

use Admin\Form\UploadEditForm;
use Admin\Form\UploadForm;
use Admin\Form\UploadShareForm;
use Admin\Service\UploadManagerService;
use Exception;
use Zend\Authentication\AuthenticationService;
use Zend\Form\Element\Select;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Log\Logger;
use Zend\Stdlib\ResponseInterface;
use Zend\View\Model\ViewModel;
use Zend\File\Transfer\Adapter\Http;

/**
 * Class UploadManagerController
 * @package Admin\Controller
 */
class UploadManagerController extends ActionController
{
    /**
     * @var UploadManagerService
     */
    protected UploadManagerService $uploadManagerService;
    /**
     * @var UploadForm<array>
     */
    protected UploadForm $uploadForm;
    /**
     * @var UploadEditForm<array>
     */
    protected UploadEditForm $uploadEditForm;
    /**
     * @var UploadShareForm<array>
     */
    protected UploadShareForm $uploadShareForm;

    /**
     * UploadManagerController constructor.
     * @param Logger $logger
     * @param AuthenticationService $authService
     * @param UploadManagerService $uploadManagerService
     * @param UploadForm<array> $uploadForm
     * @param UploadEditForm<array> $uploadEditForm
     * @param UploadShareForm<array> $uploadShareForm
     */
    public function __construct(
        Logger $logger,
        AuthenticationService $authService,
        UploadManagerService $uploadManagerService,
        UploadForm $uploadForm,
        UploadEditForm $uploadEditForm,
        UploadShareForm $uploadShareForm
    ) {
        parent::__construct($authService, $logger);
        $this->uploadManagerService = $uploadManagerService;
        $this->uploadForm = $uploadForm;
        $this->uploadEditForm = $uploadEditForm;
        $this->uploadShareForm = $uploadShareForm;
    }

    /**
     * @return ViewModel<array>
     * @throws Exception
     */
    public function indexAction(): ViewModel
    {
        $uploadTable = $this->getUploadManagerService()->getUploadTable();
        $userTable = $this->getUploadManagerService()->getUserTable();
        $user = $userTable->getUserByEmail($this->getUserEmail());
        $userId = isset($user) ? (int)$user->id : 0;

        return new ViewModel([
            'myUploads' => $uploadTable->getUploadsByUserId($userId),
            'sharedUploadsList' => $this->getUploadManagerService()->getSharedUploads($userId)
        ]);
    }

    /**
     * @return array<string, UploadForm>|Response
     * @throws Exception
     */
    public function processUploadAction()
    {
        $form = $this->getUploadForm();
        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $uploadFile = $this->params()->fromFiles('fileupload');
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $uploadPath = $this->getUploadManagerService()->getFileUploadLocation();
                if (! is_dir($uploadPath) || ! file_exists($uploadPath)) {
                    throw new Exception('Upload Path does not exist');
                }
                $adapter = new Http();
                $adapter->setDestination($uploadPath);
                $filename = basename(strval($adapter->getFileName()));
                if ($adapter->receive($filename)) {
                    $user_email = $this->getAuthService()->getStorage()->read();
                    $this->getUploadManagerService()
                        ->upload($request->getPost()->get('label'), $filename, $user_email);

                    return $this->redirect()->toRoute('admin/upload-manager', [
                        'action' => 'index'
                    ]);
                } else {
                    throw new Exception('Upload file with errors: ' .
                        implode(';', $adapter->getMessages()));
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
        return new ViewModel(['form' => $this->getUploadForm()]);
    }

    /**
     * @return Response|null
     * @throws Exception
     */
    public function processUploadEditAction(): ?Response
    {
        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form = $this->getUploadEditForm();
            $form->setData($request->getPost());
            if ($form->isValid()) {
                /** @var array<array> $data */
                $data = $form->getData();
                $uploadId = (int)$data['id'];
                $uploadTable = $this->getUploadManagerService()->getUploadTable();
                $upload = $uploadTable->getUpload($uploadId);
                /** @phpstan-ignore-next-line */
                $upload->setLabel((string)$data['label']);
                $uploadTable->saveUpload($upload);

                return $this->redirect()->toRoute('admin/upload-manager', [
                    'action' => 'index'
                ]);
            }
        }

        return null;
    }

    /**
     * @return Response
     * @throws Exception
     */
    public function deleteAction(): Response
    {
        $uploadId = (int)$this->params()->fromRoute('id');
        $this->getUploadManagerService()->delete($uploadId);

        return $this->redirect()->toRoute('admin/upload-manager');
    }

    /**
     * @return ViewModel<array>
     * @throws Exception
     */
    public function editAction(): ViewModel
    {
        $uploadId = (int)$this->params()->fromRoute('id');
        $uploadTable = $this->getUploadManagerService()->getUploadTable();
        $upload = $uploadTable->getUpload($uploadId);
        $form = $this->getUploadEditForm();
        $form->bind($upload);

        return new ViewModel([
            'form' => $form,
            'upload_id' => $uploadId,
            'sharedUsers' => $this->getUploadManagerService()->getSharedUsers($uploadId),
            'uploadShareForm' => $this->prepearUploadShareForm($uploadId),
        ]);
    }

    /**
     * @return Response
     * @throws Exception
     */
    public function processUploadShareAction(): Response
    {
        /** @var Request $request */
        $request = $this->getRequest();
        $userId = (int)$request->getPost()->get('user_id');
        $uploadId = (int)$request->getPost()->get('upload_id');
        if ($request->isPost()) {
            $uploadTable = $this->getUploadManagerService()->getUploadTable();
            $uploadTable->addSharing($uploadId, $userId);

            return $this->redirect()->toRoute(
                'admin/upload-manager',
                ['action' => 'edit', 'id' => $uploadId]
            );
        } else {
            throw new Exception('processUploadShareAction errors');
        }
    }

    /**
     * @return ResponseInterface
     * @throws Exception
     */
    public function fileDownloadAction(): ResponseInterface
    {
        $uploadId = $this->params()->fromRoute('id');
        /** @phpstan-ignore-next-line */
        return $this->getUploadManagerService()->prepearResponse($uploadId, $this->getEvent()->getResponse());
    }

    /**
     * @return Response
     * @throws Exception
     */
    public function deleteShareAction(): Response
    {
        $upload = $this->params()->fromRoute('id');
        $uploadArr = explode('-', $upload);
        if (is_array($uploadArr) && count($uploadArr) == 2) {
            $shareId = (int)$uploadArr[0];
            $uploadId = (int)$uploadArr[1];
            $uploadTable = $this->getUploadManagerService()->getUploadTable();
            $uploadTable->deleteSharedUsers($shareId);

            return $this->redirect()->toRoute(
                'admin/upload-manager',
                ['action' => 'edit', 'id' => $uploadId]
            );
        } else {
            throw new Exception('deleteShareAction errors');
        }
    }

    /**
     * @return UploadForm<array>
     */
    private function getUploadForm(): UploadForm
    {
        return $this->uploadForm;
    }

    /**
     * @return UploadEditForm<array>
     */
    private function getUploadEditForm(): UploadEditForm
    {
        return $this->uploadEditForm;
    }

    /**
     * @return UploadShareForm<array>
     */
    private function getUploadShareForm(): UploadShareForm
    {
        return $this->uploadShareForm;
    }

    /**
     * Add Additional Sharing
     * @param int $uploadId
     *
     * @return UploadShareForm<array>
     */
    private function prepearUploadShareForm(int $uploadId): UploadShareForm
    {
        $uploadShareForm = $this->getUploadShareForm();
        $userTable = $this->getUploadManagerService()->getUserTable();
        $allUsers = $userTable->fetchAll();
        $usersList = [];
        foreach ($allUsers as $user) {
            $usersList[$user->id] = $user->name;
        }
        $uploadShareForm->get('upload_id')->setValue($uploadId);
        /** @var Select $element */
        $element = $uploadShareForm->get('user_id');
        $element->setValueOptions($usersList);

        return $uploadShareForm;
    }

    /**
     * @return UploadManagerService
     */
    private function getUploadManagerService(): UploadManagerService
    {
        return $this->uploadManagerService;
    }
}
