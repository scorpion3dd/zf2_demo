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

use Admin\Service\GroupChatService;
use Exception;
use MobileTest\AbstractMock;

/**
 * Class GroupChatServiceTest
 * @package AdminTest\Service
 */
class GroupChatServiceTest extends AbstractMock
{
    private GroupChatService $groupChatService;

    public function setUp(): void
    {
        parent::setUp();

        $this->groupChatService = $this->serviceManager->get('GroupChatService');
    }

    /**
     * @throws Exception
     */
    public function testGetMessageList()
    {
        $res = $this->groupChatService->getMessageList();
        $this->assertEquals([
            0 => [
                'user' => 'admin',
                'email' => 'admin@mail.ru',
                'time' => '2021-05-04 15:38:46',
                'data' => 'Здесь могут производиться вычисления с указанием единиц измерения, тут можно складывать величины, выраженные в одних и тех же единицах измерения, можно умножать некие величины на значения, единицы измерения которых не указаны.',
            ],
            1 => [
                'user' => 'admin6',
                'email' => 'admin6@mail.ru',
                'time' => '2021-05-04 16:04:49',
                'data' => 'Hisense Smart TV быстрая установка через браузер',
            ],
            2 => [
                'user' => 'admin',
                'email' => 'admin@mail.ru',
                'time' => '2021-05-04 16:07:03',
                'data' => 'Такому рецепту меня научила бабушка. А я вам предложу. Только обычные дрожжи здесь заменены на сухие активные. Они не такие капризные, испечь куличи по бабушкиному рецепту с ними гораздо проще.',
            ],
            3 => [
                'user' => 'Sim Turner',
                'email' => 'labadie.terrence@hotmail.com',
                'time' => '2021-05-04 16:41:27',
                'data' => 'Это позволит вам использовать $this->getServiceLocator() в контроллере.',
            ],
        ], $res);
    }

    /**
     * @throws Exception
     */
    public function testGetUserTable()
    {
        $userRepository = $this->serviceManager->get('UserRepository');
        $res = $this->groupChatService->getUserTable();
        $this->assertEquals($userRepository, $res);
    }

    /**
     * @throws Exception
     */
    public function testGetLoggedInUser()
    {
        $res = $this->groupChatService->getLoggedInUser('asd@gmail.com');
        $this->assertEquals(null, $res);
    }

    /**
     * @throws Exception
     */
    public function testGetChatMessagesTableGateway()
    {
        $chatMessagesTableGateway = $this->serviceManager->get('ChatMessagesTableGateway');
        $res = $this->groupChatService->getChatMessagesTableGateway();
        $this->assertEquals($chatMessagesTableGateway, $res);
    }

    /**
     * @throws Exception
     */
    public function testSendMessage()
    {
        $res = $this->groupChatService->sendMessage('text ...', 1);
        $this->assertEquals(true, $res);
    }

    /**
     * @throws Exception
     */
    public function testGetUsersList()
    {
        $res = $this->groupChatService->getUsersList();
        $this->assertEquals([
            1 => 'admin(admin@mail.ru)',
            2 => 'admin6(admin6@mail.ru)',
            3 => 'Sim Turner(labadie.terrence@hotmail.com)'
        ], $res);
    }

    /**
     * @throws Exception
     */
    public function testSendOfflineMessage()
    {
        $res = $this->groupChatService->sendOfflineMessage('Subject', 'Text', 1, 2);
        $this->assertEquals(true, $res);
    }
}
