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

namespace Admin\Service;

use Exception;
use Interop\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Http\Request;
use Zend\Log\Logger;
use Zend\Mvc\MvcEvent;
use Zend\Router\RouteMatch;

/**
 * AuthorizationService
 * @package Admin\Service
 */
class AuthorizationService
{
    protected ContainerInterface $serviceManager;
    protected ?RouteMatch $routeMatch;
    protected Request $request;
    protected Logger $logger;
    protected AuthenticationService $authService;

    /**
     * @param MvcEvent $event
     * @param Logger $logger
     * @param AuthenticationService $authService
     */
    public function __construct(MvcEvent $event, Logger $logger, AuthenticationService $authService)
    {
        $this->setServiceManager($event->getApplication()->getServiceManager());
        $this->routeMatch = $event->getRouteMatch();
        $this->request = $this->serviceManager->get('request');
        $this->logger = $logger;
        $this->authService = $authService;
    }

    /**
     * @return bool
     */
    public function checkAuthorization(): bool
    {
        try {
            $response = true;
            $isAuthRequired = ! empty($this->routeMatch) ? $this->routeMatch->getParam('isAuthorizationRequired') : null;
            $this->logger->log(Logger::DEBUG, sprintf('is Authorization Required %s', $isAuthRequired));
            if ($isAuthRequired) {
                if (! $this->getAuthService()->hasIdentity() && getenv('APPLICATION_ENV') != 'TEST') {
                    throw new Exception('Authentication Required.', 401);
                }
                $userId = 0;
                $this->logger->log(Logger::DEBUG, sprintf('userId = ' . $userId));
                $controller = ! empty($this->routeMatch) ? $this->routeMatch->getParam('controller') : null;
                $this->logger->log(Logger::DEBUG, sprintf('controller = ' . $controller));
                $module_array = explode('\\', $controller);
                $currentModuleName = array_shift($module_array);
                $this->logger->log(Logger::DEBUG, sprintf('moduleName = ' . $currentModuleName));
                $method = $this->request->getMethod();
                $this->logger->log(Logger::DEBUG, sprintf('method = ' . $method));
            }
        } catch (Exception $exception) {
            $this->logger->log(Logger::ERR, sprintf('%s', $exception->getMessage()));
            $response = false;
        }
        $this->logger->log(Logger::DEBUG, sprintf('Authorization %s', $response));

        return $response;
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
     */
    public function setServiceManager(ContainerInterface $serviceManager): void
    {
        $this->serviceManager = $serviceManager;
    }

    /**
     * @return AuthenticationService
     */
    public function getAuthService(): AuthenticationService
    {
        return $this->authService;
    }

    /**
     * @param AuthenticationService $authService
     */
    public function setAuthService(AuthenticationService $authService): void
    {
        $this->authService = $authService;
    }
}
