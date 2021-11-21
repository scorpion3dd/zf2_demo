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

use Admin\Entity\Upload;
use Admin\Entity\User;
use Admin\Repository\UploadRepository;
use Admin\Repository\UserRepository;
use Exception;
use Zend\Http\Headers;
use Zend\Http\Response;

/**
 * Class UploadManagerService
 * @package Admin\Service
 */
class UploadManagerService extends AbstractService
{
    protected UploadRepository $uploadTable;
    protected UserRepository $userTable;

    /**
     * UploadManagerService constructor.
     * @param array<array> $config
     * @param UploadRepository $uploadTable
     * @param UserRepository $userTable
     */
    public function __construct(
        array            $config,
        UploadRepository $uploadTable,
        UserRepository   $userTable
    ) {
        parent::__construct($config);
        $this->uploadTable = $uploadTable;
        $this->userTable = $userTable;
    }

    /**
     * @return UploadRepository
     */
    public function getUploadTable(): UploadRepository
    {
        return $this->uploadTable;
    }

    /**
     * @return UserRepository
     */
    public function getUserTable(): UserRepository
    {
        return $this->userTable;
    }

    /**
     * @return mixed
     */
    public function getFileUploadLocation()
    {
        $config  = $this->getConfig();

        return $config['module_config']['upload_location'];
    }

    /**
     * @param int $userId
     *
     * @return array<array>
     * @throws Exception
     */
    public function getSharedUploads(int $userId): array
    {
        $uploadTable = $this->getUploadTable();
        $userTable = $this->getUserTable();
        $sharedUploads = $uploadTable->getSharedUploadsForUserId($userId);
        $sharedUploadsList = [];
        foreach ($sharedUploads as $sharedUpload) {
            $uploadOwner = $userTable->getUser($sharedUpload->user_id);
            $sharedUploadInfo = [];
            $sharedUploadInfo['label'] = $sharedUpload->label;
            $sharedUploadInfo['owner'] = $uploadOwner->name;
            $sharedUploadsList[$sharedUpload->id] = $sharedUploadInfo;
        }

        return $sharedUploadsList;
    }

    /**
     * @param string $label
     * @param string $filename
     * @param string $user_email
     * @throws Exception
     */
    public function upload(string $label, string $filename, string $user_email): void
    {
        $userTable = $this->getUserTable();
        $user = $userTable->getUserByEmail($user_email);
        $exchange_data = [];
        $exchange_data['label'] = $label;
        $exchange_data['filename'] = $filename;
        $exchange_data['user_id'] = $user ? $user->id : 0;
        $upload = new Upload();
        $upload->exchangeArray($exchange_data);
        $uploadTable = $this->getUploadTable();
        $uploadTable->saveUpload($upload);
    }

    /**
     * @param int $uploadId
     * @throws Exception
     */
    public function delete(int $uploadId): void
    {
        $uploadTable = $this->getUploadTable();
        $upload = $uploadTable->getUpload($uploadId);
        $uploadPath = $this->getFileUploadLocation();
        $filename = $uploadPath ."/" . $upload->filename;
        if (file_exists($filename)) {
            unlink($filename);
        }
        $uploadTable->deleteUpload($uploadId);
        $uploadTable->deleteSharedUploadByUpload($uploadId);
    }

    /**
     * @param int $uploadId
     *
     * @return array<int|string, string>
     * @throws Exception
     */
    public function getSharedUsers(int $uploadId): array
    {
        $uploadTable = $this->getUploadTable();
        $userTable = $this->getUserTable();
        $sharedUsers = [];
        $sharedUsersResult = $uploadTable->getSharedUsers($uploadId);
        foreach ($sharedUsersResult as $sharedUserRow) {
            $user = $userTable->getUser($sharedUserRow->user_id);
            $sharedUsers[$sharedUserRow->id] = $user->name;
        }

        return $sharedUsers;
    }

    /**
     * @param int $uploadId
     * @param Response $response
     *
     * @return Response
     * @throws Exception
     */
    public function prepearResponse(int $uploadId, Response $response): Response
    {
        $uploadTable = $this->getUploadTable();
        $upload = $uploadTable->getUpload($uploadId);

        $uploadPath = $this->getFileUploadLocation();
        $file = false;
        if (file_exists($uploadPath ."/" . $upload->filename)) {
            $file = file_get_contents($uploadPath ."/" . $upload->filename);
        }

        /** @var Headers $headers */
        $headers = $response->getHeaders();
        $headers->addHeaders([
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment;filename="' . $upload->filename . '"',

        ]);
        $response->setContent($file);

        return $response;
    }
}
