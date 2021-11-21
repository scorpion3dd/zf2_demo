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

namespace Mobile\Controller\Factory;

use Mobile\Controller\RestController;
use Zend\ServiceManager\ServiceManager;

/**
 * Class RestControllerFactory
 * @package Mobile\Controller
 */
class RestControllerFactory
{
    /**
     * @param ServiceManager $container
     *
     * @return RestController
     */
    public function __invoke(ServiceManager $container): RestController
    {
        $controller = new RestController(
            $container->get('Logger'),
            $container->get('AuthService'),
            $container->get('UserRepository'),
        );

        return $controller->setServiceManager($container);
    }
}
