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

use Admin\Controller\GoogleController;
use Zend\ServiceManager\ServiceManager;

/**
 * Class GoogleControllerFactory
 * @package Admin\Controller
 */
class GoogleControllerFactory
{
    /**
     * @param ServiceManager $container
     *
     * @return GoogleController
     */
    public function __invoke(ServiceManager $container): GoogleController
    {
        return new GoogleController(
            $container->get('Logger'),
            $container->get('AuthService'),
            $container->get('ImageUploadRepository'),
            $container->get('GoogleNewAlbumForm'),
            $container->get('GoogleUploadPhotoForm'),
            $container->get('GoogleSearchForm'),
            $container->get('GoogleService'),
        );
    }
}
