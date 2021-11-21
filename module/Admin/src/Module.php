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

namespace Admin;

use Admin\Controller\ActionController;
use Admin\Form\GenereteForm;
use Admin\Form\GoogleSearchForm;
use Admin\Form\Html5Form;
use Admin\Form\ImageUploadFilter;
use Admin\Form\ImageUploadForm;
use Admin\Form\MultiImageUploadForm;
use Admin\Form\ProductForm;
use Admin\Form\PurchaseForm;
use Admin\Form\SearchForm;
use Admin\Form\SendMessageForm;
use Admin\Form\SendMessageToForm;
use Admin\Helpers\LoggerHelpers;
use Admin\Service\AuthorizationService;
use Admin\Service\GoogleService;
use Admin\Service\GroupChatService;
use Admin\Service\LuceneService;
use Admin\Service\MediaManagerService;
use Admin\Service\RegisterService;
use Admin\Service\StoreAdminService;
use Admin\Service\StoreService;
use Admin\Service\UploadManagerService;
use Admin\Service\UserManagerService;
use Zend\Http\Headers;
use Zend\Http\Response;
use Zend\Log\Logger;
use Zend\Mvc\Application;
use Zend\Mvc\Controller\Plugin\Redirect;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Admin\Entity\ImageUpload;
use Admin\Repository\ImageUploadRepository;
use Admin\Entity\StoreOrder;
use Admin\Repository\StoreOrderRepository;
use Admin\Entity\StoreProduct;
use Admin\Repository\StoreProductRepository;
use Admin\Entity\Upload;
use Admin\Repository\UploadRepository;
use Admin\Entity\User;
use Admin\Repository\UserRepository;
use Admin\Form\GoogleNewAlbumForm;
use Admin\Form\LoginForm;
use Admin\Form\RegisterForm;
use Admin\Form\LoginFilter;
use Admin\Form\RegisterFilter;
use Admin\Form\UserEditForm;
use Admin\Form\UserEditFilter;
use Admin\Form\UserNewForm;
use Admin\Form\UserNewFilter;
use Admin\Form\GoogleNewAlbumFilter;
use Admin\Form\GoogleUploadPhotoForm;
use Admin\Form\GoogleUploadPhotoFilter;
use Admin\Form\UploadForm;
use Admin\Form\UploadEditForm;
use Admin\Form\UploadShareForm;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class Module
 * @package Admin
 */
class Module
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
        $config = include __DIR__ . '/../config/module.config.php';
        if (getenv('APPLICATION_ENV') == 'TEST') {
            $config['module_config']['upload_location'] = __DIR__ . '/../test/data/Service/MediaManager';
            $config['module_config']['image_upload_location'] = __DIR__ . '/../test/data/Service/MediaManager';
        }

        return $config;
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
                'Redirect' => function (ServiceLocatorInterface $sm) {
                    return $sm->get(Redirect::class);
                },
                'Config' => function (ServiceLocatorInterface $sm) {
                    return $sm->get('config');
                },

                // DB
                'UserRepository' => function (ServiceLocatorInterface $sm) {
                    $tableGateway = $sm->get('UserTableGateway');

                    return new UserRepository($tableGateway);
                },
                'UserTableGateway' => function (ServiceLocatorInterface $sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    /** @phpstan-ignore-next-line */
                    $resultSetPrototype->setArrayObjectPrototype(new User());

                    return new TableGateway('user', $dbAdapter, null, $resultSetPrototype);
                },
                'UploadRepository' => function (ServiceLocatorInterface $sm) {
                    $tableGateway = $sm->get('UploadTableGateway');
                    $uploadSharingTableGateway = $sm->get('UploadSharingTableGateway');

                    return new UploadRepository($tableGateway, $uploadSharingTableGateway);
                },
                'UploadTableGateway' => function (ServiceLocatorInterface $sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    /** @phpstan-ignore-next-line */
                    $resultSetPrototype->setArrayObjectPrototype(new Upload());

                    return new TableGateway('uploads', $dbAdapter, null, $resultSetPrototype);
                },
                'UploadSharingTableGateway' => function (ServiceLocatorInterface $sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');

                    return new TableGateway('uploads_sharing', $dbAdapter);
                },
                'ChatMessagesTableGateway' => function (ServiceLocatorInterface $sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');

                    return new TableGateway('chat_messages', $dbAdapter);
                },
                'ImageUploadRepository' => function (ServiceLocatorInterface $sm) {
                    $tableGateway = $sm->get('ImageUploadTableGateway');

                    return new ImageUploadRepository($tableGateway);
                },
                'ImageUploadTableGateway' => function (ServiceLocatorInterface $sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    /** @phpstan-ignore-next-line */
                    $resultSetPrototype->setArrayObjectPrototype(new ImageUpload());

                    return new TableGateway('image_uploads', $dbAdapter, null, $resultSetPrototype);
                },
                'WebinoImageThumb' => function (ServiceLocatorInterface $sm) {
                    return $sm->get('WebinoImageThumb');
                },
                'LoggerGlobal' => function (ServiceLocatorInterface $sm) {
                    return $sm->get('LoggerGlobal');
                },
                'Logger' => function (ServiceLocatorInterface $sm) {
                    return LoggerHelpers::prepearLogger($sm);
                },

                // DB Store Objects
                'StoreProductsTable' => function (ServiceLocatorInterface $sm) {
                    $tableGateway = $sm->get('StoreProductsTableGateway');

                    return new StoreProductRepository($tableGateway);
                },
                'StoreProductsTableGateway' => function (ServiceLocatorInterface $sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    /** @phpstan-ignore-next-line */
                    $resultSetPrototype->setArrayObjectPrototype(new StoreProduct());

                    return new TableGateway('store_products', $dbAdapter, null, $resultSetPrototype);
                },
                'StoreOrdersTable' => function (ServiceLocatorInterface $sm) {
                    $orderTableGateway = $sm->get('StoreOrdersTableGateway');
                    $productTableGateway = $sm->get('StoreProductsTableGateway');

                    return new StoreOrderRepository($orderTableGateway, $productTableGateway);
                },
                'StoreOrdersTableGateway' => function (ServiceLocatorInterface $sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    /** @phpstan-ignore-next-line */
                    $resultSetPrototype->setArrayObjectPrototype(new StoreOrder());

                    return new TableGateway('store_orders', $dbAdapter, null, $resultSetPrototype);
                },

                // FORMS
                'LoginForm' => function (ServiceLocatorInterface $sm) {
                    $form = new LoginForm();
                    $form->setInputFilter($sm->get('LoginFilter'));

                    return $form;
                },
                'RegisterForm' => function (ServiceLocatorInterface $sm) {
                    $form = new RegisterForm();
                    $form->setInputFilter($sm->get('RegisterFilter'));

                    return $form;
                },
                'UserEditForm' => function (ServiceLocatorInterface $sm) {
                    $form = new UserEditForm();
                    $form->setInputFilter($sm->get('UserEditFilter'));

                    return $form;
                },
                'UserNewForm' => function (ServiceLocatorInterface $sm) {
                    $form = new UserNewForm();
                    $form->setInputFilter($sm->get('UserNewFilter'));

                    return $form;
                },
                'MediaManagerService' => function (ServiceLocatorInterface $sm) {
                    return new MediaManagerService(
                        $sm->get('config'),
                        $sm->get('UserRepository'),
                        $sm->get('UploadRepository'),
                        $sm->get('ImageUploadRepository'),
                        $sm->get('WebinoImageThumb'),
                    );
                },
                'LuceneService' => function (ServiceLocatorInterface $sm) {
                    return new LuceneService(
                        $sm->get('config'),
                        $sm->get('UserRepository'),
                        $sm->get('UploadRepository')
                    );
                },
                'StoreAdminService' => function (ServiceLocatorInterface $sm) {
                    return new StoreAdminService(
                        $sm->get('config'),
                        $sm->get('StoreOrdersTable'),
                        $sm->get('StoreProductsTable'),
                        $sm->get('StoreOrdersTableGateway')
                    );
                },
                'StoreService' => function (ServiceLocatorInterface $sm) {
                    return new StoreService(
                        $sm->get('config'),
                        $sm->get('StoreOrdersTable'),
                        $sm->get('StoreProductsTable'),
                        $sm->get('StoreOrdersTableGateway')
                    );
                },
                'RegisterService' => function (ServiceLocatorInterface $sm) {
                    return new RegisterService(
                        $sm->get('config'),
                        $sm->get('UserRepository')
                    );
                },
                'UploadManagerService' => function (ServiceLocatorInterface $sm) {
                    return new UploadManagerService(
                        $sm->get('config'),
                        $sm->get('UploadRepository'),
                        $sm->get('UserRepository'),
                    );
                },
                'UserManagerService' => function (ServiceLocatorInterface $sm) {
                    return new UserManagerService(
                        $sm->get('config'),
                        $sm->get('UploadRepository'),
                        $sm->get('UserRepository'),
                    );
                },
                'AuthorizationService' => function (ServiceLocatorInterface $sm) {
                    return new AuthorizationService(
                        $sm->get('application')->getMvcEvent(),
                        $sm->get('Logger'),
                        $sm->get('AuthService')
                    );
                },
                'GroupChatService' => function (ServiceLocatorInterface $sm) {
                    return new GroupChatService(
                        $sm->get('config'),
                        $sm->get('UserRepository'),
                        $sm->get('ChatMessagesTableGateway')
                    );
                },
                'GoogleService' => function (ServiceLocatorInterface $sm) {
                    return new GoogleService($sm->get('config'));
                },
                'UploadForm' => function (ServiceLocatorInterface $sm) {
                    return new UploadForm();
                },
                'UploadEditForm' => function (ServiceLocatorInterface $sm) {
                    return new UploadEditForm();
                },
                'UploadShareForm' => function (ServiceLocatorInterface $sm) {
                    return new UploadShareForm();
                },
                'ImageUploadForm' => function (ServiceLocatorInterface $sm) {
                    $form = new ImageUploadForm();
                    $form->setInputFilter($sm->get('ImageUploadFilter'));

                    return $form;
                },
                'MultiImageUploadForm' => function (ServiceLocatorInterface $sm) {
                    return new MultiImageUploadForm();
                },
                'Html5Form' => function (ServiceLocatorInterface $sm) {
                    return new Html5Form();
                },
                'SendMessageForm' => function (ServiceLocatorInterface $sm) {
                    return new SendMessageForm();
                },
                'SendMessageToForm' => function (ServiceLocatorInterface $sm) {
                    return new SendMessageToForm();
                },
                'SearchForm' => function (ServiceLocatorInterface $sm) {
                    return new SearchForm();
                },
                'GenereteForm' => function (ServiceLocatorInterface $sm) {
                    return new GenereteForm();
                },
                'GoogleSearchForm' => function (ServiceLocatorInterface $sm) {
                    return new GoogleSearchForm();
                },
                'ProductForm' => function (ServiceLocatorInterface $sm) {
                    return new ProductForm();
                },
                'PurchaseForm' => function (ServiceLocatorInterface $sm) {
                    return new PurchaseForm();
                },
                'GoogleNewAlbumForm' => function (ServiceLocatorInterface $sm) {
                    $form = new GoogleNewAlbumForm();
                    $form->setInputFilter($sm->get('GoogleNewAlbumFilter'));

                    return $form;
                },
                'GoogleUploadPhotoForm' => function (ServiceLocatorInterface $sm) {
                    $form = new GoogleUploadPhotoForm();
                    $form->setInputFilter($sm->get('GoogleUploadPhotoFilter'));

                    return $form;
                },

                // FILTERS
                'LoginFilter' => function (ServiceLocatorInterface $sm) {
                    return new LoginFilter();
                },
                'RegisterFilter' => function (ServiceLocatorInterface $sm) {
                    return new RegisterFilter();
                },
                'UserEditFilter' => function (ServiceLocatorInterface $sm) {
                    return new UserEditFilter();
                },
                'UserNewFilter' => function (ServiceLocatorInterface $sm) {
                    return new UserNewFilter();
                },
                'ImageUploadFilter' => function (ServiceLocatorInterface $sm) {
                    return new ImageUploadFilter();
                },
                'GoogleNewAlbumFilter' => function (ServiceLocatorInterface $sm) {
                    return new GoogleNewAlbumFilter();
                },
                'GoogleUploadPhotoFilter' => function (ServiceLocatorInterface $sm) {
                    return new GoogleUploadPhotoFilter();
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
        $eventManager->attach(MvcEvent::EVENT_ROUTE, function (MvcEvent $e) use ($logger, $serviceManager) {
            $authorizationAdapter = new AuthorizationService($e, $logger, $serviceManager->get('AuthService'));
            if (! $authorizationAdapter->checkAuthorization()) {
                /** @var Application $obj */
                $obj = $e->getTarget();
                /** @var Response $response */
                $response = $obj->getResponse();
                /** @var Headers $headers */
                $headers = $response->getHeaders();
                $headers->addHeaderLine('Location', 'login');
                $response->setStatusCode(302);
                /** @phpstan-ignore-next-line */
                $response->sendHeaders();
                $e->stopPropagation();

                return $response;
            }
        });
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $sharedEventManager = $eventManager->getSharedManager();

        $sharedEventManager->attach(__NAMESPACE__, MvcEvent::EVENT_DISPATCH, function (MvcEvent $e) use ($logger) {
            /** @var ActionController $controller */
            $controller = $e->getTarget();
            $controllerName = $controller->getEvent()->getRouteMatch()->getParam('controller');
            $logger->log(Logger::INFO, 'controller', [$controllerName]);
            if (! in_array($controllerName, [
                'Admin\Controller\Index',
                'Admin\Controller\Register',
                'Admin\Controller\Login',
                'Admin\Controller\UploadManager',
                'Admin\Controller\UserManager',
                'Admin\Controller\GroupChat',
                'Admin\Controller\Store',
                'Admin\Controller\Google'
            ])) {
                $controller->layout('layout/myaccount');
                $userEmail = $controller->getAuthService()->getStorage()->read();
                /** @phpstan-ignore-next-line */
                $controller->layout()->setVariable('userEmail', $userEmail);
                $logger->log(Logger::INFO, 'userEmail', [$userEmail]);
            }
        });
    }
}
