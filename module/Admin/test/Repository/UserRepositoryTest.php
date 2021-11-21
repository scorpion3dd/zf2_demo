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

namespace AdminTest\Repository;

use Admin\Entity\User;
use Admin\Repository\UserRepository;
use Exception;
use MobileTest\AbstractMock;

/**
 * Class UserRepositoryTest
 * @package AdminTest\Repository
 */
class UserRepositoryTest extends AbstractMock
{
    private UserRepository $userRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->userRepository = $this->serviceManager->get('UserRepository');
    }

    /**
     * @throws Exception
     */
    public function testSaveUser()
    {
        $user = new User();
        $user->exchangeArray([
            'id' => '30',
            'password' => 'd8578edf8458ce06fbc5bb76a58c5ca4',
            'confirmPassword' => 'd8578edf8458ce06fbc5bb76a58c5ca4',
            'name' => 'Ivan',
            'email' => 'ivan@gmail.com',
            'birthday' => '1985-07-30',
            'phone' => '0987779988',
            'address' => 'London',
        ]);
        $res = $this->userRepository->saveUser($user);
        $this->assertEquals(30, $res);
    }

    /**
     * @throws Exception
     */
    public function testGetUser()
    {
        $user = new User();
        $user->exchangeArray([
            'id' => 1,
            'password' => '21232f297a57a5a743894a0e4a801fc3',
            'confirmPassword' => '',
            'name' => 'admin',
            'email' => 'admin@mail.ru',
            'birthday' => '2021-09-23',
            'phone' => '46456-8989',
            'description' => 'By default',
            'address' => 'Kiev',
            'type' => 1,
            'created' => '2021-10-03 01:12:11',
            'updated' => '2021-10-03 01:12:12',
        ]);
        $res = $this->userRepository->getUser(1);
        $this->assertEquals($user, $res);
    }

    /**
     * @throws Exception
     */
    public function testGetUserByName()
    {
        $user = new User();
        $data = [
            'id' => 1,
            'password' => '21232f297a57a5a743894a0e4a801fc3',
            'confirmPassword' => '',
            'name' => 'admin',
            'email' => 'admin@mail.ru',
            'birthday' => '2021-09-23',
            'phone' => '46456-8989',
            'description' => 'By default',
            'address' => 'Kiev',
            'type' => 1,
            'created' => '2021-10-03 01:12:11',
            'updated' => '2021-10-03 01:12:12',
        ];
        $user->exchangeArray($data);
        $res = $this->userRepository->getUserByName('admin');
        $this->assertEquals([0 => $data], $res);
    }

    /**
     * @throws Exception
     */
    public function testGetUserByEmail()
    {
        $user = new User();
        $user->exchangeArray([
            'id' => 1,
            'password' => '21232f297a57a5a743894a0e4a801fc3',
            'confirmPassword' => '',
            'name' => 'admin',
            'email' => 'admin@mail.ru',
            'birthday' => '2021-09-23',
            'phone' => '46456-8989',
            'description' => 'By default',
            'address' => 'Kiev',
            'type' => 1,
            'created' => '2021-10-03 01:12:11',
            'updated' => '2021-10-03 01:12:12',
        ]);
        $res = $this->userRepository->getUserByEmail('admin@mail.ru');
        $this->assertEquals($user, $res);
    }

    /**
     * @throws Exception
     */
    public function testDeleteUser()
    {
        $res = $this->userRepository->deleteUser(100);
        $this->assertEquals(null, $res);
    }
}
