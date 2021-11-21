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

use Mobile\Controller\MobileController;
use Zend\ServiceManager\ServiceManager;

/**
 * Class MobileControllerFactory
 * @package Mobile\Controller
 */
class MobileControllerFactory
{
    /**
     * @param ServiceManager $container
     *
     * @return MobileController
     */
    public function __invoke(ServiceManager $container): MobileController
    {
        $controller = new MobileController(
            $container->get('Logger'),
            $container->get('AuthService'),
        );

        return $controller->setServiceManager($container);
    }
}
