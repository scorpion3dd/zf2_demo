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

namespace AdminTest\Service;

use Admin\Entity\User;
use Admin\Service\RegisterService;
use Exception;
use MobileTest\AbstractMock;

/**
 * Class RegisterServiceTest
 * @package AdminTest\Service
 */
class RegisterServiceTest extends AbstractMock
{
    private RegisterService $registerService;

    public function setUp(): void
    {
        parent::setUp();

        $this->registerService = $this->serviceManager->get('RegisterService');
    }

    /**
     * @throws Exception
     */
    public function testGetUserTable()
    {
        $userRepository = $this->serviceManager->get('UserRepository');
        $res = $this->registerService->getUserTable();
        $this->assertEquals($userRepository, $res);
    }

    /**
     * @throws Exception
     */
    public function testCreateProduct()
    {
        $user = new User();
        $user->exchangeArray([
            'id' => '20',
            'password' => 'd8578edf8458ce06fbc5bb76a58c5ca4',
            'confirmPassword' => 'd8578edf8458ce06fbc5bb76a58c5ca4',
            'name' => 'Ivan',
            'email' => 'ivan@gmail.com',
            'birthday' => '1985-07-30',
            'phone' => '0987779988',
            'address' => 'London',
        ]);
        $res = $this->registerService->createUser([
            'id' => '20',
            'password' => 'qwerty',
            'confirmPassword' => 'qwerty',
            'name' => 'Ivan',
            'email' => 'ivan@gmail.com',
            'birthday' => '1985-07-30',
            'phone' => '0987779988',
            'address' => 'London',
        ]);
        $this->assertEquals($user, $res);
    }
}
