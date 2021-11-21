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

namespace Mobile;

use Admin\Helpers\LoggerHelpers;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;
use Zend\Authentication\AuthenticationService;
use Zend\Log\Logger;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class Module
 * @package Mobile/Module
 * https://stackoverflow.com/questions/28411743/uncaught-exception-zend-modulemanager-exception-runtimeexception-with-message
 * Module (Mobile) could not be initialized
 * composer update --ignore-platform-reqs
 */
class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{
    const VERSION = '1.1.1dev';

    /**
     * @return array<array>
     */
    public function getAutoloaderConfig(): array
    {
        return [
            'Zend\Loader\ClassMapAutoloader' => [
                __DIR__ . '/autoload_classmap.php',
            ],
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/', __NAMESPACE__),
                ],
            ],
        ];
    }

    /**
     * @return array<array>
     */
    public function getConfig(): array
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * @return array<array>
     */
    public function getServiceConfig(): array
    {
        return [
            'abstract_factories' => [],
            'aliases' => [],
            'factories' => [
                // SERVICES
                'AuthService' => function (ServiceLocatorInterface $sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $dbTableAuthAdapter = new DbTableAuthAdapter(
                        $dbAdapter,
                        'user',
                        'email',
                        'password',
                        'MD5(?)'
                    );
                    $authService = new AuthenticationService();
                    $authService->setAdapter($dbTableAuthAdapter);

                    return $authService;
                },
                'Config' => function (ServiceLocatorInterface $sm) {
                    return $sm->get('config');
                },
                'LoggerGlobal' => function (ServiceLocatorInterface $sm) {
                    return $sm->get('LoggerGlobal');
                },
                'Logger' => function (ServiceLocatorInterface $sm) {
                    return LoggerHelpers::prepearLogger($sm);
                },
            ],
            'invokables' => [],
            'services' => [],
            'shared' => [],
        ];
    }

    /**
     * @param MvcEvent $e
     */
    public function onBootstrap(MvcEvent $e): void
    {
        $eventManager = $e->getApplication()->getEventManager();
        $serviceManager = $e->getApplication()->getServiceManager();
        $logger = LoggerHelpers::prepearLogger($serviceManager);
        foreach (['dispatch.error', 'render.error'] as $row) {
            $eventManager->attach($row, function ($e) use ($logger, $row) {
                $exception = $e->getParam('exception');
                if (! $exception) {
                    return;
                }
                $logger->log(Logger::ALERT, 'exception', [$row]);
                throw $exception;
            });
        }
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $sharedEventManager = $eventManager->getSharedManager();
        $sharedEventManager->attach(__NAMESPACE__, MvcEvent::EVENT_DISPATCH, function ($e) use ($logger) {
            $controller = $e->getTarget();
            $controllerName = $controller->getEvent()->getRouteMatch()->getParam('controller');
            $logger->log(Logger::INFO, 'controller', [$controllerName]);
            if (in_array($controllerName, ['Mobile\Controller\MobileController'])) {
                $controller->layout('layout/mobile');
                $userEmail = $controller->getAuthService()->getStorage()->read();
                $controller->layout()->setVariable('userEmail', $userEmail);
                $logger->log(Logger::INFO, 'userEmail', [$userEmail]);
            }
        });
    }
}
