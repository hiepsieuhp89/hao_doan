<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Setting\Controller\Index' => 'Setting\Controller\IndexController',
            'Setting\Controller\Acticre' => 'Setting\Controller\ActicreController',
           
            
        ),
    ),
    'router' => array(
        'routes' => array(
             'Setting' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/system/setting[/:action[/:id][/:status][/page-:page]][.html]',
                    'constraints' => array(                        
                        'action' => '[a-zA-Z][a-zA-Z0-9-]*',
                        'id' => '[1-9][0-9]*',
                        'status' => '[0-9]*',
                        'page' => '[1-9][0-9]*'
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Setting\Controller',
                        'controller' => 'Setting\Controller\Index',
                        'action' => 'index',
                        
                    )
                )
            ),
            'ActicreAd' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/system/acticre[/:action[/:id][/:status][/page-:page]][.html]',
                    'constraints' => array(                        
                        'action' => '[a-zA-Z][a-zA-Z0-9-]*',
                        'id' => '[1-9][0-9]*',
                        'status' => '[0-9]*',
                        'page' => '[1-9][0-9]*'
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Setting\Controller',
                        'controller' => 'Setting\Controller\Acticre',
                        'action' => 'index',
                        
                    )
                )
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
            __DIR__ . '/../view',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'action' => 'Eva\View\Helper\Action',
        ),
    ),
);
