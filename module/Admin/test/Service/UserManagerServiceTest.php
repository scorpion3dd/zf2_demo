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
use Admin\Service\UserManagerService;
use Exception;
use MobileTest\AbstractMock;

/**
 * Class UserManagerServiceTest
 * @package AdminTest\Service
 */
class UserManagerServiceTest extends AbstractMock
{
    private UserManagerService $userManagerService;

    public function setUp(): void
    {
        parent::setUp();

        $this->userManagerService = $this->serviceManager->get('UserManagerService');
    }

    /**
     * @throws Exception
     */
    public function testGetUploadTable()
    {
        $uploadRepository = $this->serviceManager->get('UploadRepository');
        $res = $this->userManagerService->getUploadTable();
        $this->assertEquals($uploadRepository, $res);
    }

    /**
     * @throws Exception
     */
    public function testGetUserTable()
    {
        $userRepository = $this->serviceManager->get('UserRepository');
        $res = $this->userManagerService->getUserTable();
        $this->assertEquals($userRepository, $res);
    }

    /**
     * @throws Exception
     */
    public function testPrepearUserSuccess()
    {
        $request = $this->getRequest();
        $post = $request->getPost();
        $post->offsetSet('name', 'Ivan');
        $post->offsetSet('email', 'ivan@gmail.com');
        $post->offsetSet('birthday', '1985-07-30');
        $post->offsetSet('phone', '0987779988');
        $post->offsetSet('address', 'London');
        $post->offsetSet('password', 'qwerty');
        $post->offsetSet('confirmPassword', 'qwerty');
        $res = $this->userManagerService->prepearUser($post);

        $user = new User();
        $user->exchangeArray($post);
        $user->setPassword($post->get('password'));
        $user->setConfirmPassword($post->get('confirmPassword'));

        $this->assertEquals($user, $res);
    }

    /**
     * @throws Exception
     */
    public function testDelete()
    {
        $res = $this->userManagerService->delete(100);
        $this->assertNull($res);
    }
}
