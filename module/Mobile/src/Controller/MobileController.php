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

namespace Mobile\Controller;

use Admin\Controller\ActionController;
use Zend\Authentication\AuthenticationService;
use Zend\Log\Logger;
use Zend\View\Model\ViewModel;

/**
 * Class MobileController
 * @package Mobile\Controller
 */
class MobileController extends ActionController
{
    /**
     * MobileController constructor.
     * @param Logger $logger
     * @param AuthenticationService $authService
     */
    public function __construct(Logger $logger, AuthenticationService $authService)
    {
        parent::__construct($authService, $logger);
    }

    /**
     * @return ViewModel<array>
     */
    public function indexAction(): ViewModel
    {
        return new ViewModel();
    }
}
