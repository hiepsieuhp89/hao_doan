<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'User\Controller\Index' => 'User\Controller\IndexController',
            'User\Controller\Manage' => 'User\Controller\ManageController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'login' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/system',
                    'defaults' => array(
                        'controller' => 'User\Controller\Index',
                        'action' => 'index',
                    ),
                ),
            ),
            'homeadmin' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/system/home',
                    'defaults' => array(
                        'controller' => 'User\Controller\Manage',
                        'action' => 'home',
                    ),
                ),
            ),
            'listuser' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/system/manageuser.html',
                    'defaults' => array(
                        'controller' => 'User\Controller\Manage',
                        'action' => 'index',
                    ),
                ),
            ),
            'adduser' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/system/add-user.html',
                    'defaults' => array(
                        'controller' => 'User\Controller\Manage',
                        'action' => 'adduser',
                    ),
                ),
            ),
            'updateuser' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/system/update-user[-:id].html',
                    'defaults' => array(
                        'controller' => 'User\Controller\Manage',
                        'action' => 'updateuser',
                    ),
                ),
            ),
            /*'updateuser' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/update-user[-:id].html',
                    'defaults' => array(
                        'controller' => 'System\Controller\Manage',
                        'action' => 'updateuser',
                    ),
                ),
            ),*/
            'changerpass' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/system/changepass-user[-:id].html',
                    'constraints' => array(
                                'id' => '[0-9]*',
                           ),
                    'defaults' => array(
                        'controller' => 'User\Controller\Manage',
                        'action' => 'changpass',
                    ),
                ),
            ),
            'deleteus' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/system/delete-user[-:id].html',
                    'constraints' => array(
                                'id' => '[0-9]*',
                           ),
                    'defaults' => array(
                        'controller' => 'User\Controller\Manage',
                        'action' => 'deleteus',
                    ),
                ),
            ),
            'logout' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/system/logout.html',
                    'defaults' => array(
                        'controller' => 'User\Controller\Index',
                        'action' => 'logout',
                    ),
                ),
            ),
            'ajaxreset' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/system/ajaxreset[/:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'User\Controller\Manage',
                        'action' => 'resetpass',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            'system' => __DIR__ . '/../view/',
        ),
    ),
);
