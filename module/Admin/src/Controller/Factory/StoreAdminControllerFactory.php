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

use Admin\Controller\StoreAdminController;
use Zend\ServiceManager\ServiceManager;

/**
 * Class StoreAdminControllerFactory
 * @package Admin\Controller
 */
class StoreAdminControllerFactory
{
    /**
     * @param ServiceManager $container
     *
     * @return StoreAdminController
     */
    public function __invoke(ServiceManager $container): StoreAdminController
    {
        return new StoreAdminController(
            $container->get('Logger'),
            $container->get('AuthService'),
            $container->get('StoreAdminService'),
            $container->get('ProductForm')
        );
    }
}
