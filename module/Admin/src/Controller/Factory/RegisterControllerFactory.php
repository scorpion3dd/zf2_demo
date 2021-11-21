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

use Admin\Controller\RegisterController;
use Zend\ServiceManager\ServiceManager;

/**
 * Class RegisterControllerFactory
 * @package Admin\Controller
 */
class RegisterControllerFactory
{
    /**
     * @param ServiceManager $container
     *
     * @return RegisterController
     */
    public function __invoke(ServiceManager $container): RegisterController
    {
        return new RegisterController(
            $container->get('Logger'),
            $container->get('RegisterForm'),
            $container->get('RegisterService'),
            $container->get('AuthService')
        );
    }
}
