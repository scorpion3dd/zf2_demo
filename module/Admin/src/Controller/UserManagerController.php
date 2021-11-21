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

use Admin\Form\UserEditForm;
use Admin\Form\UserNewForm;
use Admin\Service\UserManagerService;
use Exception;
use Zend\Authentication\AuthenticationService;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Log\Logger;
use Zend\View\Model\ViewModel;

/**
 * Class UserManagerController
 * @package Admin\Controller
 */
class UserManagerController extends ActionController
{
    /**
     * @var UserManagerService
     */
    private UserManagerService $userManagerService;
    /**
     * @var UserEditForm<array>
     */
    private UserEditForm $userEditForm;
    /**
     * @var UserNewForm<array>
     */
    private UserNewForm $userNewForm;

    /**
     * UserManagerController constructor.
     * @param Logger $logger
     * @param UserManagerService $userManagerService
     * @param UserEditForm<array> $userEditForm
     * @param UserNewForm<array> $userNewForm
     * @param AuthenticationService $authService
     */
    public function __construct(
        Logger $logger,
        UserManagerService $userManagerService,
        UserEditForm $userEditForm,
        UserNewForm $userNewForm,
        AuthenticationService $authService
    ) {
        parent::__construct($authService, $logger);
        $this->userManagerService = $userManagerService;
        $this->userEditForm = $userEditForm;
        $this->userNewForm = $userNewForm;
    }

    /**
     * @return ViewModel<array>
     */
    public function indexAction(): ViewModel
    {
        return new ViewModel(['users' => $this->getUserManagerService()->getUserTable()->fetchAll()]);
    }

    /**
     * @return ViewModel<array>
     */
    public function newAction(): ViewModel
    {
        return new ViewModel(['form' => $this->getUserNewForm()]);
    }

    /**
     * @return ViewModel<array>
     * @throws Exception
     */
    public function editAction(): ViewModel
    {
        $userId = $this->params()->fromRoute('id');
        $user = $this->getUserManagerService()->getUserTable()->getUser($userId);
        $form = $this->getUserEditForm();
        $form->bind($user);

        return new ViewModel(['form' => $form, 'user_id' => $userId]);
    }

    /**
     * @return Response|ViewModel<array>
     * @throws Exception
     */
    public function processAction()
    {
        /** @var Request $request */
        $request = $this->getRequest();
        if (! $request->isPost()) {
            return $this->redirect()->toRoute('admin/user-manager', ['action' => 'edit']);
        }
        $post = $request->getPost();
        $user = $this->getUserManagerService()->prepearUser($post);
        $form = $this->getUserEditForm();
        $form->bind($user);
        $form->setData($post);
        if (! $form->isValid()) {
            $model = new ViewModel([
                'error' => true,
                'form'  => $form,
            ]);
            $model->setTemplate('admin/user-manager/edit');

            return $model;
        }
        try {
            $this->getUserManagerService()->getUserTable()->saveUser($user);
        } catch (Exception $e) {
            $this->getLogger()->err($e->getMessage(), ['UserManagerController']);
        }

        return $this->redirect()->toRoute('admin/user-manager');
    }

    /**
     * @return Response
     */
    public function deleteAction(): Response
    {
        $userId = (int)$this->params()->fromRoute('id');
        $this->getUserManagerService()->delete($userId);

        return $this->redirect()->toRoute('admin/user-manager');
    }

    /**
     * @return UserEditForm<array>
     */
    private function getUserEditForm(): UserEditForm
    {
        return $this->userEditForm;
    }

    /**
     * @return UserNewForm<array>
     */
    private function getUserNewForm(): UserNewForm
    {
        return $this->userNewForm;
    }

    /**
     * @return UserManagerService
     */
    private function getUserManagerService(): UserManagerService
    {
        return $this->userManagerService;
    }
}
