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

use Admin\Entity\ImageUpload;
use Exception;
use Zend\Db\ResultSet\ResultSetInterface;
use Zend\Db\TableGateway\TableGateway;

/**
 * Class ImageUploadRepository
 * @package Admin\Repository
 */
class ImageUploadRepository extends AbstractRepository
{
    /**
     * ImageUploadRepository constructor.
     * @param TableGateway $tableGateway
     */
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    /**
     * @param ImageUpload $upload
     * @throws Exception
     */
    public function saveUpload(ImageUpload $upload):void
    {
        $data = [
            'filename' => $upload->filename,
            'thumbnail' => $upload->thumbnail,
            'label' => $upload->label,
            'user_id' => $upload->user_id,
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
     * @return ImageUpload
     * @throws Exception
     */
    public function getUpload(int $uploadId): ImageUpload
    {
        /** @var ResultSetInterface<array> $rowset */
        $rowset = $this->getTableGateway()->select(['id' => $uploadId]);
        /** @phpstan-ignore-next-line */
        $row = $rowset->current();
        if (! $row) {
            throw new Exception("Could not find row $uploadId");
        }

        return $row;
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
     *
     * @return ResultSetInterface<array>|null
     */
    public function getUploadsByUserId(int $userId): ?ResultSetInterface
    {
        return $this->getTableGateway()->select(['user_id' => $userId]);
    }
}
