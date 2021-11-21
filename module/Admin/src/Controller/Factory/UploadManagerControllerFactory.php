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

use Admin\Controller\UploadManagerController;
use Zend\ServiceManager\ServiceManager;

/**
 * Class UploadManagerControllerFactory
 * @package Admin\Controller
 */
class UploadManagerControllerFactory
{
    /**
     * @param ServiceManager $container
     *
     * @return UploadManagerController
     */
    public function __invoke(ServiceManager $container): UploadManagerController
    {
        return new UploadManagerController(
            $container->get('Logger'),
            $container->get('AuthService'),
            $container->get('UploadManagerService'),
            $container->get('UploadForm'),
            $container->get('UploadEditForm'),
            $container->get('UploadShareForm'),
        );
    }
}
