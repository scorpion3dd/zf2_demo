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

namespace Admin\Repository;

use Admin\Entity\Upload;
use Exception;
use Zend\Db\ResultSet\ResultSetInterface;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;

/**
 * Class UploadRepository
 * @package Admin\Repository
 */
class UploadRepository extends AbstractRepository
{
    protected TableGateway $uploadSharingTableGateway;

    /**
     * UploadRepository constructor.
     * @param TableGateway $tableGateway
     * @param TableGateway $uploadSharingTableGateway
     */
    public function __construct(TableGateway $tableGateway, TableGateway $uploadSharingTableGateway)
    {
        $this->tableGateway = $tableGateway;
        $this->uploadSharingTableGateway = $uploadSharingTableGateway;
    }

    /**
     * @param Upload $upload
     * @throws Exception
     */
    public function saveUpload(Upload $upload): void
    {
        $data = [
            'filename' => $upload->filename,
            'label'  => $upload->label,
            'user_id'  => $upload->user_id,
        ];
        $id = (int)$upload->id;
        if ($id == 0) {
            $this->getTableGateway()->insert($data);
        } else {
            $this->getUpload($id);
            $this->getTableGateway()->update($data, ['id' => $id]);
        }
    }

    /**
     * @param int $uploadId
     *
     * @return Upload|null
     * @throws Exception
     */
    public function getUpload(int $uploadId): ?Upload
    {
        $rowset = $this->getTableGateway()->select(['id' => $uploadId]);
        /** @var Upload $upload */
        /** @phpstan-ignore-next-line */
        $upload = $rowset->current();

        return $upload;
    }

    /**
     * @param int $uploadId
     */
    public function deleteUpload(int $uploadId): void
    {
        $this->getTableGateway()->delete(['id' => $uploadId]);
    }

    /**
     * @param int $userId
     */
    public function deleteUploadByUser(int $userId): void
    {
        $this->getTableGateway()->delete(['user_id' => $userId]);
    }

    /**
     * @param int $uploadId
     */
    public function deleteSharedUploadByUpload(int $uploadId): void
    {
        $this->getUploadSharingTableGateway()->delete(['upload_id' => $uploadId]);
    }

    /**
     * @param int $userId
     */
    public function deleteSharedUploadByUser(int $userId): void
    {
        $this->getUploadSharingTableGateway()->delete(['user_id' => $userId]);
    }

    /**
     * Uploads for the user
     * @param int $userId
     *
     * @return ResultSetInterface<array>|null
     */
    public function getUploadsByUserId(int $userId): ?ResultSetInterface
    {
        return $this->getTableGateway()->select(['user_id' => $userId]);
    }

    /**
     * Uploads shared with the user
     * @param int $userId
     *
     * @return ResultSetInterface<array>|null
     */
    public function getSharedUploadsForUserId(int $userId): ?ResultSetInterface
    {
        $rowset = $this->getUploadSharingTableGateway()
            ->select(function (Select $select) use ($userId) {
                $select->columns([]) // no columns from main table
                    ->where(['uploads_sharing.user_id' => $userId])
                    ->join('uploads', 'uploads_sharing.upload_id = uploads.id');
            });

        return $rowset;
    }

    /**
     * Uploads shared with the user
     * @param int $uploadId
     *
     * @return ResultSetInterface<array>|null
     */
    public function getSharedUsers(int $uploadId): ?ResultSetInterface
    {
        return $this->getUploadSharingTableGateway()->select(['upload_id' => $uploadId]);
    }

    /**
     * @param int $uploadId
     */
    public function deleteSharedUsers(int $uploadId): void
    {
        $this->getUploadSharingTableGateway()->delete(['id' => $uploadId]);
    }

    /**
     * Uploads shared with the user
     * @param int $uploadId
     * @param int $userId
     */
    public function addSharing(int $uploadId, int $userId): void
    {
        $data = [
            'upload_id' => $uploadId,
            'user_id'  => $userId,
        ];
        $this->getUploadSharingTableGateway()->delete($data);
        $this->getUploadSharingTableGateway()->insert($data);
    }

    /**
     * Uploads shared with the user
     * @param int $uploadId
     * @param int $userId
     */
    public function removeSharing(int $uploadId, int $userId): void
    {
        $data = [
            'upload_id' => $uploadId,
            'user_id'  => $userId,
        ];
        $this->getUploadSharingTableGateway()->insert($data);
    }

    /**
     * @return TableGateway
     */
    public function getUploadSharingTableGateway(): TableGateway
    {
        return $this->uploadSharingTableGateway;
    }
}
