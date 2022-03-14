<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Product\Controller\Index' => 'Product\Controller\IndexController',
            'Product\Controller\Category' => 'Product\Controller\CategoryController',
            'Product\Controller\Manufacture' => 'Product\Controller\ManufactureController',
            'Product\Controller\Xuatxu' => 'Product\Controller\XuatxuController',
            'Product\Controller\Chatlieu' => 'Product\Controller\ChatlieuController',
            
        ),
    ),
    'router' => array(
        'routes' => array(
             'Product' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/system/product[/:action[/:id][/:status][trash-:trash][show=:show]][.html][/page=:page]',
                    'constraints' => array(                        
                        'action' => '[a-zA-Z][a-zA-Z0-9-]*',
                        'id' => '[1-9][0-9]*',
                        'status' => '[0-9]*',
                        'trash' => '[0-9]*',
                        'show' => '[0-9]*',
                        'page' => '[1-9][0-9]*'
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Product\Controller',
                        'controller' => 'Product\Controller\Index',
                        'action' => 'index',
                        
                    )
                )
            ),
          
             'CategoryProduct' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/system/product/category[/:action[/:id][/:status][show=:show][/page-:page]][.html]',
                    'constraints' => array(                        
                        'action' => '[a-zA-Z][a-zA-Z0-9-]*',
                        'id' => '[1-9][0-9]*',
                        'status' => '[0-9]*',
                        'page' => '[1-9][0-9]*',
                        'show' => '[0-9]*',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Product\Controller',
                        'controller' => 'Product\Controller\Category',
                        'action' => 'index',
                        
                    )
                )
            ),
            'Manufacture' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/system/product/manufacture[/:action[/:id][/:status][/page-:page]][.html]',
                    'constraints' => array(                        
                        'action' => '[a-zA-Z][a-zA-Z0-9-]*',
                        'id' => '[1-9][0-9]*',
                        'status' => '[0-9]*',
                        'page' => '[1-9][0-9]*'
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Product\Controller',
                        'controller' => 'Product\Controller\Manufacture',
                        'action' => 'index',
                        
                    )
                )
            ),
            'Xuatxu' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/system/product/xuatxu[/:action[/:id][/:status][/page-:page]][.html]',
                    'constraints' => array(                        
                        'action' => '[a-zA-Z][a-zA-Z0-9-]*',
                        'id' => '[1-9][0-9]*',
                        'status' => '[0-9]*',
                        'page' => '[1-9][0-9]*'
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Product\Controller',
                        'controller' => 'Product\Controller\Xuatxu',
                        'action' => 'index',
                        
                    )
                )
            ),
             'Chatlieu' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/system/product/chatlieu[/:action[/:id][/:status][/page-:page]][.html]',
                    'constraints' => array(                        
                        'action' => '[a-zA-Z][a-zA-Z0-9-]*',
                        'id' => '[1-9][0-9]*',
                        'status' => '[0-9]*',
                        'page' => '[1-9][0-9]*'
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Product\Controller',
                        'controller' => 'Product\Controller\Chatlieu',
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
