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

use Zend\Authentication\Adapter\ValidatableAdapterInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Log\Logger;
use Zend\View\Model\ViewModel;
use Admin\Form\LoginForm;

/**
 * Class LoginController
 * @package Admin\Controller
 */
class LoginController extends ActionController
{
    /** @var LoginForm<array> $loginForm */
    private LoginForm $loginForm;

    /**
     * LoginController constructor.
     * @param Logger $logger
     * @param AuthenticationService $authService
     * @param LoginForm<array> $loginForm
     */
    public function __construct(
        Logger $logger,
        AuthenticationService $authService,
        LoginForm $loginForm
    ) {
        parent::__construct($authService, $logger);
        $this->loginForm = $loginForm;
    }

    /**
     * @return Response
     */
    public function logoutAction(): Response
    {
        $this->getAuthService()->clearIdentity();

        return $this->redirect()->toRoute('admin/login');
    }

    /**
     * @return ViewModel<array>
     */
    public function indexAction(): ViewModel
    {
        return new ViewModel(['form' => $this->getLoginForm()]);
    }

    /**
     * @return Response|ViewModel<array>
     */
    public function processAction()
    {
        /** @var Request $request */
        $request = $this->getRequest();
        if (! $request->isPost()) {
            return $this->redirect()->toRoute('admin/login');
        }
        $post = $request->getPost();
        $form = $this->getLoginForm();
        $form->setData($post);
        if (! $form->isValid()) {
            $model = new ViewModel([
                'error' => true,
                'form'  => $form,
            ]);
            $model->setTemplate('admin/login/index');

            return $model;
        } else {
            /** @var ValidatableAdapterInterface $adapter */
            $adapter = $this->getAuthService()->getAdapter();
            $email = $request->getPost('email');
            $adapter->setIdentity($email)->setCredential($request->getPost('password'));
            $result = $this->getAuthService()->authenticate();
            if ($result->isValid()) {
                $this->getAuthService()->getStorage()->write($email);

                return $this->redirect()->toRoute('admin/login', ['action' => 'confirm']);
            } else {
                $model = new ViewModel([
                    'error' => true,
                    'form'  => $form,
                ]);
                $model->setTemplate('admin/login/index');

                return $model;
            }
        }
    }

    /**
     * @return ViewModel<array>
     */
    public function confirmAction(): ViewModel
    {
        return new ViewModel([
            'user_email' => $this->getAuthService()->getStorage()->read()
        ]);
    }

    /**
     * @return LoginForm<array>
     */
    private function getLoginForm(): LoginForm
    {
        return $this->loginForm;
    }
}
