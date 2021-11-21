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

use Admin\Service\RegisterService;
use Exception;
use Zend\Authentication\AuthenticationService;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Log\Logger;
use Zend\View\Model\ViewModel;
use Admin\Form\RegisterForm;

/**
 * Class RegisterController
 * @package Admin\Controller
 */
class RegisterController extends ActionController
{
    /** @var RegisterForm<array> $registerForm */
    private RegisterForm $registerForm;
    /** @var RegisterService $registerService */
    private RegisterService $registerService;

    /**
     * RegisterController constructor.
     * @param Logger $logger
     * @param RegisterForm<array> $registerForm
     * @param RegisterService $registerService
     * @param AuthenticationService $authService
     */
    public function __construct(
        Logger $logger,
        RegisterForm $registerForm,
        RegisterService $registerService,
        AuthenticationService $authService
    ) {
        parent::__construct($authService, $logger);
        $this->registerForm = $registerForm;
        $this->registerService = $registerService;
    }

    /**
     * @return ViewModel<array>
     */
    public function indexAction(): ViewModel
    {
        return new ViewModel(['form' => $this->getRegisterForm()]);
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
            return $this->redirect()->toRoute('admin/register');
        }
        $post = $request->getPost();
        $form = $this->getRegisterForm();
        $form->setData($post);
        if (! $form->isValid()) {
            $model = new ViewModel([
                'error' => true,
                'form'  => $form,
            ]);
            $model->setTemplate('admin/register/index');

            return $model;
        }
        /** @phpstan-ignore-next-line */
        $this->getRegisterService()->createUser($form->getData());

        return $this->redirect()->toRoute('admin/register', ['action' => 'confirm']);
    }

    /**
     * @return ViewModel<array>
     */
    public function confirmAction(): ViewModel
    {
        return new ViewModel();
    }

    /**
     * @return RegisterForm<array>
     */
    private function getRegisterForm(): RegisterForm
    {
        return $this->registerForm;
    }

    /**
     * @return RegisterService
     */
    private function getRegisterService(): RegisterService
    {
        return $this->registerService;
    }
}
