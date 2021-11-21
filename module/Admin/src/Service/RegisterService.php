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
use Admin\Repository\UserRepository;
use Exception;
use Zend\Stdlib\ParametersInterface;

/**
 * Class RegisterService
 * @package Admin\Service
 */
class RegisterService extends AbstractService
{
    private UserRepository $userTable;

    /**
     * RegisterService constructor.
     * @param array<array> $config
     * @param UserRepository $userTable
     */
    public function __construct(array $config, UserRepository $userTable)
    {
        parent::__construct($config);
        $this->userTable = $userTable;
    }

    /**
     * @return UserRepository
     */
    public function getUserTable(): UserRepository
    {
        return $this->userTable;
    }

    /**
     * @param ParametersInterface<array>|array<array> $data
     *
     * @return User
     * @throws Exception
     */
    public function createUser($data): User
    {
        $user = new User();
        $user->exchangeArray($data);
        $user->setPassword($data['password']);
        $user->setConfirmPassword($data['confirmPassword']);
        $this->getUserTable()->saveUser($user);

        return $user;
    }
}
