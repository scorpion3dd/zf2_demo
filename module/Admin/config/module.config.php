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

use Admin\Controller\Factory\GoogleControllerFactory;
use Admin\Controller\Factory\GroupChatControllerFactory;
use Admin\Controller\Factory\Html5ControllerFactory;
use Admin\Controller\Factory\IndexControllerFactory;
use Admin\Controller\Factory\LoginControllerFactory;
use Admin\Controller\Factory\LuceneControllerFactory;
use Admin\Controller\Factory\MediaManagerControllerFactory;
use Admin\Controller\Factory\RegisterControllerFactory;
use Admin\Controller\Factory\StoreAdminControllerFactory;
use Admin\Controller\Factory\StoreControllerFactory;
use Admin\Controller\Factory\UploadManagerControllerFactory;
use Admin\Controller\Factory\UserManagerControllerFactory;
use Admin\Controller\GoogleController;
use Admin\Controller\GroupChatController;
use Admin\Controller\Html5Controller;
use Admin\Controller\IndexController;
use Admin\Controller\LoginController;
use Admin\Controller\LuceneController;
use Admin\Controller\MediaManagerController;
use Admin\Controller\RegisterController;
use Admin\Controller\StoreAdminController;
use Admin\Controller\StoreController;
use Admin\Controller\UploadManagerController;
use Admin\Controller\UserManagerController;
use Admin\Service\GoogleService;
use Admin\Service\GroupChatService;
use Admin\Service\LuceneService;
use Admin\Service\MediaManagerService;
use Admin\Service\RegisterService;
use Admin\Service\StoreAdminService;
use Admin\Service\StoreService;
use Admin\Service\UploadManagerService;
use Admin\Service\UserManagerService;
use Admin\Service\AuthorizationService;
use Zend\Router\Http\Literal;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'controllers' => [
        'factories' => [
            IndexController::class => IndexControllerFactory::class,
            RegisterController::class => RegisterControllerFactory::class,
            LoginController::class => LoginControllerFactory::class,
            UserManagerController::class => UserManagerControllerFactory::class,
            UploadManagerController::class => UploadManagerControllerFactory::class,
            GroupChatController::class => GroupChatControllerFactory::class,
            MediaManagerController::class => MediaManagerControllerFactory::class,
            GoogleController::class => GoogleControllerFactory::class,
            LuceneController::class => LuceneControllerFactory::class,
            StoreController::class => StoreControllerFactory::class,
            StoreAdminController::class => StoreAdminControllerFactory::class,
            Html5Controller::class => Html5ControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            GoogleService::class => InvokableFactory::class,
            GroupChatService::class => InvokableFactory::class,
            LuceneService::class => InvokableFactory::class,
            MediaManagerService::class => InvokableFactory::class,
            RegisterService::class => InvokableFactory::class,
            StoreAdminService::class => InvokableFactory::class,
            StoreService::class => InvokableFactory::class,
            UploadManagerService::class => InvokableFactory::class,
            UserManagerService::class => InvokableFactory::class,
            AuthorizationService::class => InvokableFactory::class,
        ]
    ],
    'router' => [
        'routes' => [
            'admin' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/admin',
                    'defaults' => [
                        'controller'    => IndexController::class,
                        'action'        => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'login' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/login[/:action]',
                            'constraints' => [
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ],
                            'defaults' => [
                                'controller'    => LoginController::class,
                                'action'        => 'index',
                            ],
                        ],
                    ],
                    'register' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/register[/:action]',
                            'constraints' => [
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ],
                            'defaults' => [
                                'controller'    => RegisterController::class,
                                'action'        => 'index',
                            ],
                        ],
                    ],
                    'user-manager' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/user-manager[/:action[/:id]]',
                            'constraints' => [
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '[a-zA-Z0-9_-]*',
                            ],
                            'defaults' => [
                                'controller' => UserManagerController::class,
                                'isAuthorizationRequired' => true,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                    'upload-manager' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/upload-manager[/:action[/:id]]',
                            'constraints' => [
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '[a-zA-Z0-9_-]*',
                            ],
                            'defaults' => [
                                'controller' => UploadManagerController::class,
                                'isAuthorizationRequired' => true,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                    'group-chat' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/group-chat[/:action[/:id]]',
                            'constraints' => [
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '[a-zA-Z0-9_-]*',
                            ],
                            'defaults' => [
                                'controller' => GroupChatController::class,
                                'isAuthorizationRequired' => true,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                    'media' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/media[/:action[/:id[/:subaction]]]',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[a-zA-Z0-9_-]*',
                                'subaction' => '[a-zA-Z][a-zA-Z0-9_-]*',

                            ],
                            'defaults' => [
                                'controller' => MediaManagerController::class,
                                'isAuthorizationRequired' => true,
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'google' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/google[/:action[/:albumId]]',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'albumId' => '[a-zA-Z][a-zA-Z0-9_-]*'
                            ],
                            'defaults' => [
                                'controller' => GoogleController::class,
                                'isAuthorizationRequired' => true,
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'lucene' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/lucene[/:action[/:id]]',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[a-zA-Z][a-zA-Z0-9_-]*'
                            ],
                            'defaults' => [
                                'controller' => LuceneController::class,
                                'isAuthorizationRequired' => true,
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'store' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/store[/:action[/:id[/:subaction]]]',
                            'constraints' => [
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '[a-zA-Z0-9_-]*',
                                'subaction'     => '[a-zA-Z][a-zA-Z0-9_-]*',

                            ],
                            'defaults' => [
                                'controller' => StoreController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                    'store-admin' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/store-admin[/:action[/:id[/:subaction]]]',
                            'constraints' => [
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '[a-zA-Z0-9_-]*',
                                'subaction'     => '[a-zA-Z][a-zA-Z0-9_-]*',

                            ],
                            'defaults' => [
                                'controller' => StoreAdminController::class,
                                'isAuthorizationRequired' => true,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                    'html5' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/html5[/:action]',
                            'constraints' => [
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*'

                            ],
                            'defaults' => [
                                'controller' => Html5Controller::class,
                                'isAuthorizationRequired' => true,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'admin' => __DIR__ . '/../view',
        ],
        'template_map' => [
            'layout/myaccount' => __DIR__ . '/../view/layout/myaccount.phtml',
        ],
    ],
    'module_config' => [
        'upload_location' => __DIR__ . '/../../../data/uploads',
        'image_upload_location' => __DIR__ . '/../../../data/images',
        'search_index' => __DIR__ . '/../../../data/search_index',
        'google_keys' => __DIR__ . '/../../../config/google/googleusercontent.json'
    ],
    'speck-paypal-api' => [
        'username'              => '<USERNAME>',
        'password'              => '<PASSWORD>',
        'signature'             => '<SIGNATURE>',
        'endpoint'               => 'https://api-3t.sandbox.paypal.com/nvp'
    ]
];
