<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Authorization;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

use Doctrine\DBAL\Driver\PDOPgSql\Driver as PDOPgSql;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/authorization',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'authorization' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/authorization[/:action]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'params' => [
                    'driver'   	=> 'pdo_pgsql',
                    'host'     	=> 'gibdd-postgres',
                    'user'     	=> 'pguser',
                    'password' 	=> 'Qwerty123',
                    'dbname'   =>  'mi_auth',
                ]
            ],
        ],
    ],

    'crypt' => [
        'cryptKeyUserData' => 'c0Kl2mc7akBz64fE',
        'cryptSaltUserData' => 'c67v02j52x41bw1d',
    ],

    'ais' => [
        'CAS_SERVER' => 'https://10.11.32.130:8282',
        'SERVICE_URL' => 'http://10.11.32.115:1717',
        'REQUEST_DENIED' => 'samlp:RequestDenied',
        'SUDIS_ACCESS' => 'ROLE_SUDIS_MI_ACCESS'
    ],

];