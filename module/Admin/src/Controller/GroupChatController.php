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

namespace Admin\Controller;

use Admin\Form\SendMessageForm;
use Admin\Form\SendMessageToForm;
use Admin\Service\GroupChatService;
use Exception;
use Zend\Authentication\AuthenticationService;
use Zend\Form\Element;
use Zend\Http\Request;
use Zend\Log\Logger;
use Zend\View\Model\ViewModel;
use Zend\Http\Response;

/**
 * Class GroupChatController
 * @package Admin\Controller
 */
class GroupChatController extends ActionController
{
    /** @var GroupChatService $groupChatService */
    private GroupChatService $groupChatService;
    /** @var SendMessageForm<array> $sendMessageForm */
    private SendMessageForm $sendMessageForm;
    /** @var SendMessageToForm<array> $sendMessageToForm */
    private SendMessageToForm $sendMessageToForm;

    /**
     * GroupChatController constructor.
     * @param Logger $logger
     * @param AuthenticationService $authService
     * @param SendMessageForm<array> $sendMessageForm
     * @param SendMessageToForm<array> $sendMessageToForm
     * @param GroupChatService $groupChatService
     */
    public function __construct(
        Logger $logger,
        AuthenticationService $authService,
        SendMessageForm $sendMessageForm,
        SendMessageToForm $sendMessageToForm,
        GroupChatService $groupChatService
    ) {
        parent::__construct($authService, $logger);
        $this->groupChatService = $groupChatService;
        $this->sendMessageForm = $sendMessageForm;
        $this->sendMessageToForm = $sendMessageToForm;
    }

    /**
     * @return Response|ViewModel<array>
     * @throws Exception
     */
    public function indexAction()
    {
        $user = $this->getGroupChatService()->getLoggedInUser($this->getUserEmail());
        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost() && $user) {
            $messageTest = $request->getPost()->get('message');
            $fromUserId = (int)$user->id;
            $this->getGroupChatService()->sendMessage($messageTest, $fromUserId);
            // to prevent duplicate entries on refresh

            return $this->redirect()->toRoute('admin/group-chat');
        }

        return new ViewModel(['form' => $this->getSendMessageForm(), 'userName' => $user ? $user->name : '']);
    }

    /**
     * @return ViewModel<array>
     * @throws Exception
     */
    public function messageListAction(): ViewModel
    {
        $viewModel  = new ViewModel([
            'messageList' => $this->getGroupChatService()->getMessageList(),
            'email' => $this->getUserEmail()
        ]);
        $viewModel->setTemplate('admin/group-chat/message-list');
        $viewModel->setTerminal(true);

        return $viewModel;
    }

    /**
     * @return Response|ViewModel<array>
     * @throws Exception
     */
    public function sendOfflineMessageAction()
    {
        $user = $this->getGroupChatService()->getLoggedInUser($this->getUserEmail());
        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost() && $user) {
            $msgSubj = $request->getPost()->get('messageSubject');
            $msgText = $request->getPost()->get('message');
            $toUser = $request->getPost()->get('toUserId');
            $fromUser = $user->id;
            $this->getGroupChatService()->sendOfflineMessage($msgSubj, $msgText, $fromUser, $toUser);
            // to prevent duplicate entries on refresh

            return $this->redirect()->toRoute(
                'admin/group-chat',
                ['action' => 'sendOfflineMessage'],
                ['query' => ['status' => 'success']]
            );
        }
        $form = $this->getSendMessageToForm();
        /** @var Element\Select $element */
        $element = $form->get('toUserId');
        $element->setValueOptions($this->getGroupChatService()->getUsersList());
        $status = $request->getQuery()->get('status');

        return new ViewModel(['form' => $form,
            'userName' => $user ? $user->name : '',
            'status' => $status
        ]);
    }

    /**
     * @return SendMessageForm<array>
     */
    private function getSendMessageForm(): SendMessageForm
    {
        return $this->sendMessageForm;
    }

    /**
     * @return SendMessageToForm<array>
     */
    private function getSendMessageToForm(): SendMessageToForm
    {
        return $this->sendMessageToForm;
    }

    /**
     * @return GroupChatService
     */
    private function getGroupChatService(): GroupChatService
    {
        return $this->groupChatService;
    }
}
