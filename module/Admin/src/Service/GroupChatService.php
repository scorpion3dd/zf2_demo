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

use Zend\Db\TableGateway\TableGateway;
use Zend\Mail;
use Admin\Entity\User;
use Admin\Repository\UserRepository;
use DateTime;
use Exception;

/**
 * Class GroupChatService
 * @package Admin\Service
 */
class GroupChatService extends AbstractService
{
    private UserRepository $userTable;
    private TableGateway $chatMessagesTableGateway;

    /**
     * GroupChatService constructor.
     * @param array<array> $config
     * @param UserRepository $userTable
     * @param TableGateway $chatMessagesTableGateway
     */
    public function __construct(array $config, UserRepository $userTable, TableGateway $chatMessagesTableGateway)
    {
        parent::__construct($config);
        $this->userTable = $userTable;
        $this->chatMessagesTableGateway = $chatMessagesTableGateway;
    }

    /**
     * @return UserRepository
     */
    public function getUserTable(): UserRepository
    {
        return $this->userTable;
    }

    /**
     * @param string|null $userEmail
     *
     * @return User|null
     * @throws Exception
     */
    public function getLoggedInUser(?string $userEmail): ?User
    {
        return $this->getUserTable()->getUserByEmail($userEmail);
    }

    /**
     * @return TableGateway
     */
    public function getChatMessagesTableGateway(): TableGateway
    {
        return $this->chatMessagesTableGateway;
    }

    /**
     * @param string $messageTest
     * @param int $fromUserId
     *
     * @return bool
     */
    public function sendMessage(string $messageTest, int $fromUserId): bool
    {
        $now = new DateTime();
        $data = [
            'user_id' => $fromUserId,
            'message'  => $messageTest,
            'stamp' => $now->format('Y-m-d H:i:s')
        ];
        $this->getChatMessagesTableGateway()->insert($data);

        return true;
    }

    /**
     * @return array<array>
     * @throws Exception
     */
    public function getMessageList(): array
    {
        $userTable = $this->getUserTable();
        $chatMessageTG = $this->getChatMessagesTableGateway();
        $chatMessages = $chatMessageTG->select();
        $messageList = [];
        foreach ($chatMessages as $chatMessage) {
            $fromUser = $userTable->getUser($chatMessage->user_id);
            $messageData = [];
            $messageData['user'] = $fromUser->name;
            $messageData['email'] = $fromUser->email;
            $messageData['time'] = $chatMessage->stamp;
            $messageData['data'] = $chatMessage->message;
            $messageList[] = $messageData;
        }

        return $messageList;
    }

    /**
     * @return array<int|string, string>
     */
    public function getUsersList(): array
    {
        $allUsers = $this->getUserTable()->fetchAll();
        $usersList = [];
        foreach ($allUsers as $user) {
            $usersList[$user->id] = $user->name . '(' . $user->email . ')';
        }

        return $usersList;
    }

    /**
     * @param string $msgSubj
     * @param string $msgText
     * @param int $fromUserId
     * @param int $toUserId
     *
     * @return bool
     * @throws Exception
     */
    public function sendOfflineMessage(string $msgSubj, string $msgText, int $fromUserId, int $toUserId): bool
    {
        $userTable = $this->getUserTable();
        $fromUser = $userTable->getUser($fromUserId);
        $toUser = $userTable->getUser($toUserId);

        $mail = new Mail\Message();
        $mail->setFrom($fromUser->email, $fromUser->name);
        $mail->addTo($toUser->email, $toUser->name);
        $mail->setSubject($msgSubj);
        $mail->setBody($msgText);

        $transport = new Mail\Transport\Sendmail();
        $transport->send($mail);

        return true;
    }
}
