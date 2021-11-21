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

use Admin\Controller\StoreController;
use Zend\ServiceManager\ServiceManager;

/**
 * Class StoreControllerFactory
 * @package Admin\Controller
 */
class StoreControllerFactory
{
    /**
     * @param ServiceManager $container
     *
     * @return StoreController
     */
    public function __invoke(ServiceManager $container): StoreController
    {
        return new StoreController(
            $container->get('Logger'),
            $container->get('AuthService'),
            $container->get('StoreService'),
            $container->get('PurchaseForm'),
        );
    }
}
