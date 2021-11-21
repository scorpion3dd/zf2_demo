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

namespace AdminTest\Controller;

use Admin\Controller\GroupChatController;
use Exception;
use MobileTest\AbstractMock;

/**
 * Class GroupChatControllerTest
 * @package AdminTest\Controller
 */
class GroupChatControllerTest extends AbstractMock
{
    /**
     * @throws Exception
     */
    public function testIndexActionSuccess()
    {
        $this->dispatch('/admin/group-chat', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('admin');
        $this->assertControllerName(GroupChatController::class);
        $this->assertControllerClass('GroupChatController');
        $this->assertMatchedRouteName('admin/group-chat');
        $response = $this->getResponse()->getContent();
        self::assertTrue($this->HTMLValidate($response));
        $expected = file_get_contents(
            __DIR__ . '/../../test/data/Controller/GroupChat/GetIndexActionSuccess.html'
        );
        self::assertSame($this->trim($expected), $this->trim($response));
    }

    /**
     * @throws Exception
     */
    public function testMessageListActionSuccess()
    {
        $this->dispatch('/admin/group-chat/message-list', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('admin');
        $this->assertControllerName(GroupChatController::class);
        $this->assertControllerClass('GroupChatController');
        $this->assertMatchedRouteName('admin/group-chat');
        $response = $this->getResponse()->getContent();
        self::assertTrue($this->HTMLValidate($response));
        $expected = file_get_contents(
            __DIR__ . '/../../test/data/Controller/GroupChat/GetMessageListActionSuccess.html'
        );
        self::assertSame($this->trim($expected), $this->trim($response));
    }

    /**
     * @throws Exception
     */
    public function testSendOfflineMessageActionSuccess()
    {
        $this->dispatch('/admin/group-chat/send-offline-message', 'POST');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('admin');
        $this->assertControllerName(GroupChatController::class);
        $this->assertControllerClass('GroupChatController');
        $this->assertMatchedRouteName('admin/group-chat');
        $response = $this->getResponse()->getContent();
        $expected = file_get_contents(
            __DIR__ . '/../../test/data/Controller/GroupChat/PostSendOfflineMessageActionSuccess.html'
        );
//        self::assertSame($this->trim($expected), $this->trim($response));
    }

    /**
     * @throws Exception
     */
    public function testInvalidRoute()
    {
        $this->dispatch('/invalid/route', 'GET');
        $this->assertResponseStatusCode(404);
    }
}
