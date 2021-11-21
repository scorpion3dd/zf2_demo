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

use Admin\Repository\UserRepository;
use Zend\Authentication\AuthenticationService;
use Zend\Log\Logger;
use Zend\View\Model\ViewModel;

/**
 * Class IndexController
 * @package Admin\Controller
 */
class IndexController extends ActionController
{
    private UserRepository $userTable;

    /**
     * IndexController constructor.
     * @param Logger $logger
     * @param AuthenticationService $authService
     * @param UserRepository $userTable
     */
    public function __construct(
        Logger                $logger,
        AuthenticationService $authService,
        UserRepository        $userTable
    ) {
        parent::__construct($authService, $logger);
        $this->userTable = $userTable;
    }

    /**
     * @return ViewModel<array>
     */
    public function indexAction(): ViewModel
    {
        if (isset($this->userEmail)) {
            $view = new ViewModel(['users' => $this->getUserTable()->fetchAll()]);
            $template = 'admin/user-manager/index';
        } else {
            $view = new ViewModel();
            $template = 'admin/index/index';
        }
        $view->setTemplate($template);

        return $view;
    }

    /**
     * @return UserRepository
     */
    private function getUserTable(): UserRepository
    {
        return $this->userTable;
    }
}
