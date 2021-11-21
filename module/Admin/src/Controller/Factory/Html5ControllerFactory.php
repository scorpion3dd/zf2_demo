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

use Admin\Controller\Html5Controller;
use Zend\ServiceManager\ServiceManager;

/**
 * Class Html5ControllerFactory
 * @package Admin\Controller
 */
class Html5ControllerFactory
{
    /**
     * @param ServiceManager $container
     *
     * @return Html5Controller
     */
    public function __invoke(ServiceManager $container): Html5Controller
    {
        return new Html5Controller(
            $container->get('Logger'),
            $container->get('AuthService'),
            $container->get('Html5Form'),
            $container->get('MultiImageUploadForm'),
        );
    }
}
