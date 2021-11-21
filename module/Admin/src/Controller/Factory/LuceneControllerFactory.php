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

use Admin\Controller\LuceneController;
use Zend\ServiceManager\ServiceManager;

/**
 * Class LuceneControllerFactory
 * @package Admin\Controller
 */
class LuceneControllerFactory
{
    /**
     * @param ServiceManager $container
     *
     * @return LuceneController
     */
    public function __invoke(ServiceManager $container): LuceneController
    {
        return new LuceneController(
            $container->get('Logger'),
            $container->get('AuthService'),
            $container->get('SearchForm'),
            $container->get('GenereteForm'),
            $container->get('LuceneService'),
        );
    }
}
