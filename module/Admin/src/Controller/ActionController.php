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

namespace Admin\Controller;

use Psr\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Log\Logger;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Class ActionController
 * @package Admin\Controller
 */
class ActionController extends AbstractActionController
{
    protected ContainerInterface $serviceManager;
    protected AuthenticationService $authService;
    protected ?string $userEmail;
    protected Logger $logger;

    /**
     * ActionController constructor.
     * @param AuthenticationService $authService
     * @param Logger $logger
     */
    public function __construct(AuthenticationService $authService, Logger $logger)
    {
        $this->logger = $logger;
        $this->authService = $authService;
        $this->userEmail = $this->getAuthService()->getStorage()->read();
    }

    /**
     * @return AuthenticationService
     */
    public function getAuthService(): AuthenticationService
    {
        return $this->authService;
    }

    /**
     * @return string|null
     */
    public function getUserEmail(): ?string
    {
        return $this->userEmail;
    }

    /**
     * @return Logger
     */
    public function getLogger(): Logger
    {
        return $this->logger;
    }

    /**
     * @return ContainerInterface
     */
    public function getServiceManager(): ContainerInterface
    {
        return $this->serviceManager;
    }

    /**
     * @param ContainerInterface $serviceManager
     *
     * @return $this
     */
    public function setServiceManager(ContainerInterface $serviceManager): ActionController
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }
}
