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

use Mobile\Controller\Factory\MobileControllerFactory;
use Mobile\Controller\Factory\RestControllerFactory;
use Mobile\Controller\MobileController;
use Mobile\Controller\RestController;
use Zend\Router\Http\Literal;

return [
    'router' => [
        'routes' => [
            'mobile' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/mobile',
                    'defaults' => [
                        'controller'    => MobileController::class,
                        'action'        => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'v1' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/v1[/:action]',
                            'constraints' => [
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ],
                            'defaults' => [
                                'controller'    => MobileController::class,
                                'action'        => 'index',
                            ],
                        ],
                    ],
                    'r1' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/r1[/:action[/:id]]',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '[a-zA-Z0-9_-]*',
                            ],
                            'defaults' => [
                                'controller'    => RestController::class,
                                'action'        => 'index',
                            ],
                        ],
                    ],
                ]
            ]
        ],
    ],
    'controllers' => [
        'factories' => [
            MobileController::class => MobileControllerFactory::class,
            RestController::class => RestControllerFactory::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/mobile'       => __DIR__ . '/../view/layout/default.phtml',
            'mobile/mobile/index' => __DIR__ . '/../view/mobile/mobile/index.phtml',
        ],
        'template_path_stack' => [
            'mobile' => __DIR__ . '/../view',
        ],
    ],
];
