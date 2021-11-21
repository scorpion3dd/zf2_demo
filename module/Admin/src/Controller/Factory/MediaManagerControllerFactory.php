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

use Admin\Controller\MediaManagerController;
use Zend\ServiceManager\ServiceManager;

/**
 * Class MediaManagerControllerFactory
 * @package Admin\Controller
 */
class MediaManagerControllerFactory
{
    /**
     * @param ServiceManager $container
     *
     * @return MediaManagerController
     */
    public function __invoke(ServiceManager $container): MediaManagerController
    {
        return new MediaManagerController(
            $container->get('Logger'),
            $container->get('AuthService'),
            $container->get('ImageUploadForm'),
            $container->get('MediaManagerService'),
        );
    }
}
