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

use Admin\Entity\User;
use Admin\Repository\UploadRepository;
use Admin\Repository\UserRepository;
use Exception;
use Zend\Stdlib\ParametersInterface;

/**
 * Class UserManagerService
 * @package Admin\Service
 */
class UserManagerService extends AbstractService
{
    protected UploadRepository $uploadTable;
    protected UserRepository $userTable;

    /**
     * UserManagerService constructor.
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
     * @param ParametersInterface<array> $post
     *
     * @return User
     * @throws Exception
     */
    public function prepearUser(ParametersInterface $post): User
    {
        if (isset($post->id) && $post->id > 0) {
            $userTable = $this->getUserTable();
            $user = $userTable->getUser($post->id);
        } else {
            $user = new User();
            $user->exchangeArray($post);
            $user->setPassword($post->get('password'));
            $user->setConfirmPassword($post->get('confirmPassword'));
        }

        return $user;
    }

    /**
     * @param int $userId
     */
    public function delete(int $userId): void
    {
        $this->getUserTable()->deleteUser($userId);
        $this->getUploadTable()->deleteUploadByUser($userId);
        $this->getUploadTable()->deleteSharedUploadByUser($userId);
    }
}
