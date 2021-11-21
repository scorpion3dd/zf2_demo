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

use Admin\Controller\UserManagerController;
use Zend\ServiceManager\ServiceManager;

/**
 * Class UserManagerControllerFactory
 * @package Admin\Controller
 */
class UserManagerControllerFactory
{
    /**
     * @param ServiceManager $container
     *
     * @return UserManagerController
     */
    public function __invoke(ServiceManager $container): UserManagerController
    {
        return new UserManagerController(
            $container->get('Logger'),
            $container->get('UserManagerService'),
            $container->get('UserEditForm'),
            $container->get('UserNewForm'),
            $container->get('AuthService')
        );
    }
}
