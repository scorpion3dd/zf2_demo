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

namespace Admin\Controller\Factory;

use Admin\Controller\LoginController;
use Zend\ServiceManager\ServiceManager;

/**
 * Class LoginControllerFactory
 * @package Admin\Controller
 */
class LoginControllerFactory
{
    /**
     * @param ServiceManager $container
     *
     * @return LoginController
     */
    public function __invoke(ServiceManager $container): LoginController
    {
        return new LoginController(
            $container->get('Logger'),
            $container->get('AuthService'),
            $container->get('LoginForm')
        );
    }
}
