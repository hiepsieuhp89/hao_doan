<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Fronts\Controller\Index' => 'Fronts\Controller\IndexController',
            'Fronts\Controller\Elements' => 'Fronts\Controller\ElementsController',
            'Fronts\Controller\Acount' => 'Fronts\Controller\AcountController',
             'Fronts\Controller\Product' => 'Fronts\Controller\ProductController',
             'Fronts\Controller\Shoppingcart' => 'Fronts\Controller\ShoppingcartController',
            'Fronts\Controller\News' => 'Fronts\Controller\NewsController',
            'Fronts\Controller\Contact' => 'Fronts\Controller\ContactController',
            'Fronts\Controller\Setting' => 'Fronts\Controller\SettingController',
            
        ),
    ),
    'router' => array(
        'routes' => array(
            //Viết các router tại đây
            'homeindex' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Fronts\Controller',
                        'controller' => 'Fronts\Controller\index',
                        'action' => 'index',
                    ),
                ),
            ),
            // ACOUNT CONTROLLER
            'dangky' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/tai-khoan/dang-ky.html',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Fronts\Controller',
                        'controller' => 'Fronts\Controller\Acount',
                        'action' => 'register',
                    ),
                ),
            ),
             'Dangnhap' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/tai-khoan/dang-nhap.html',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Fronts\Controller',
                        'controller' => 'Fronts\Controller\Acount',
                        'action' => 'login',
                    ),
                ),
            ),
            
            'Logout' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/tai-khoan/logout.html',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Fronts\Controller',
                        'controller' => 'Fronts\Controller\Acount',
                        'action' => 'logout',
                    ),
                ),
            ),
             'Personal' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/tai-khoan/personal.html',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Fronts\Controller',
                        'controller' => 'Fronts\Controller\Acount',
                        'action' => 'personal',
                    ),
                ),
            ),
            'Infomation' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/tai-khoan/thong-tin-tai-khoan.html',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Fronts\Controller',
                        'controller' => 'Fronts\Controller\Acount',
                        'action' => 'information',
                    ),
                ),
            ),
             'Resetpass' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/tai-khoan/reset-password.html',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Fronts\Controller',
                        'controller' => 'Fronts\Controller\Acount',
                        'action' => 'resetpass',
                    ),
                ),
            ),
            
             'Order' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/tai-khoan/quan-ly-don-hang.html[/page=:page]',
                    'constraints' => array(                         
                         'page' => '[0-9]*',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Fronts\Controller',
                        'controller' => 'Fronts\Controller\Acount',
                        'action' => 'order',
                    ),
                ),
            ),
            
            //// Product CONTROLLER
            'Sanphamdetail' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/san-pham[/:alias].html',
                     'constraints' => array(                        
                        'alias' => '[A-za-z0-9_-]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Fronts\Controller',
                        'controller' => 'Fronts\Controller\Product',
                        'action' => 'productdetail',
                    ),
                ),
            ),
             'Danhmucsanpham' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/san-pham/danh-muc[/:alias].html[/page=:page]',
                     'constraints' => array(                        
                         'alias' => '[a-zA-Z][a-zA-Z0-9-]*',
                        'id' => '[1-9][0-9]*',
                         'page' => '[0-9]*',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Fronts\Controller',
                        'controller' => 'Fronts\Controller\Product',
                        'action' => 'categoryproduct',
                    ),
                ),
            ),
            'Hangsanxuat' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/san-pham/hang-san-xuat[/:alias].html[/page=:page]',
                     'constraints' => array(                        
                         'alias' => '[a-zA-Z][a-zA-Z0-9-]*',
                        'id' => '[1-9][0-9]*',
                         'page' => '[0-9]*',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Fronts\Controller',
                        'controller' => 'Fronts\Controller\Product',
                        'action' => 'productmanufa',
                    ),
                ),
            ),
            'Sanpham' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/san-pham.html[/page=:page]',
                     'constraints' => array(                        
                        'page' => '[0-9]*',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Fronts\Controller',
                        'controller' => 'Fronts\Controller\Product',
                        'action' => 'index',
                    ),
                ),
            ),
            'Search' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/tim-kiem.html[/page=:page]',
                     'constraints' => array(                        
                        'page' => '[0-9]*',
                         
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Fronts\Controller',
                        'controller' => 'Fronts\Controller\Product',
                        'action' => 'search',
                    ),
                ),
            ),
             'FilterProduct' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/sanpham/filter',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Fronts\Controller',
                        'controller' => 'Fronts\Controller\Product',
                        'action' => 'filter',
                    ),
                ),
            ),
			 'Loadproduct_Ajax' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/sanpham/searchkeyup',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Fronts\Controller',
                        'controller' => 'Fronts\Controller\Product',
                        'action' => 'searchajax',
                    ),
                ),
            ),
            
            //SHOPPING CART
             'Addcart' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/shoppingcart/add-cart.html',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Fronts\Controller',
                        'controller' => 'Fronts\Controller\Shoppingcart',
                        'action' => 'addcart',
                    ),
                ),
            ),
            'Updatecart' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/shoppingcart/updatecart',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Fronts\Controller',
                        'controller' => 'Fronts\Controller\Shoppingcart',
                        'action' => 'updatecart',
                    ),
                ),
            ),
            'Bynow' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/shoppingcart/bynow',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Fronts\Controller',
                        'controller' => 'Fronts\Controller\Shoppingcart',
                        'action' => 'bynow',
                    ),
                ),
            ),
            'Postcheckout' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/shoppingcart/postcheckout',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Fronts\Controller',
                        'controller' => 'Fronts\Controller\Shoppingcart',
                        'action' => 'postcheckout',
                    ),
                ),
            ),
            'Viewcart' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/shoppingcart/view-cart.html',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Fronts\Controller',
                        'controller' => 'Fronts\Controller\Shoppingcart',
                        'action' => 'viewcart',
                    ),
                ),
            ),
            'ViewcartMobile' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/shoppingcart/mb-view-cart.html',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Fronts\Controller',
                        'controller' => 'Fronts\Controller\Shoppingcart',
                        'action' => 'viewcartmobile',
                    ),
                ),
            ),
            'Checkout' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/shoppingcart/checkout.html',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Fronts\Controller',
                        'controller' => 'Fronts\Controller\Shoppingcart',
                        'action' => 'checkout',
                    ),
                ),
            ),
             'Checkoutfull' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/shoppingcart/checkoutsucess.html',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Fronts\Controller',
                        'controller' => 'Fronts\Controller\Shoppingcart',
                        'action' => 'checkoutsucess',
                    ),
                ),
            ),
            'Deletecart' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/shoppingcart/delete-items-cart[/:id]',
                     'constraints' => array(                        
                        'id' => '[0-9]*',
                    ),
                    'defaults' => array(
                       '__NAMESPACE__' => 'Fronts\Controller',
                        'controller' => 'Fronts\Controller\Shoppingcart',
                        'action' => 'deletecart',
                    ),
                ),
            ),
            'ClearAllcart' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/shoppingcart/clear-all-cart',
                     'constraints' => array(                        
                        'id' => '[0-9]*',
                    ),
                    'defaults' => array(
                       '__NAMESPACE__' => 'Fronts\Controller',
                        'controller' => 'Fronts\Controller\Shoppingcart',
                        'action' => 'clearcart',
                    ),
                ),
            ),
			
            
            // Contronler tin tuc
            'ViewTin' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/tin-tuc[/:alias].html',
                     'constraints' => array(                        
                         'alias' => '[a-zA-Z0-9][a-zA-Z0-9-]*',
                       
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Fronts\Controller',
                        'controller' => 'Fronts\Controller\News',
                        'action' => 'view',
                    ),
                ),
            ),
            'Tintuc' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/tin-tuc.html[/page=:page]',
                     'constraints' => array(                 
                        'page' => '[0-9]*',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Fronts\Controller',
                        'controller' => 'Fronts\Controller\News',
                        'action' => 'index',
                    ),
                ),
            ),
            
            // Contronler contact
            'Lienhe' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/lien-he.html',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Fronts\Controller',
                        'controller' => 'Fronts\Controller\Contact',
                        'action' => 'index',
                    ),
                ),
            ),
            //Controoler setting
            'Acticre' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/suport[/:alias].html',
                     'constraints' => array(                        
                         'alias' => '[a-zA-Z][a-zA-Z0-9-]*',                       
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Fronts\Controller',
                        'controller' => 'Fronts\Controller\Setting',
                        'action' => 'suport',
                    ),
                ),
            ),
            
            'About' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/gioi-thieu.html',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Fronts\Controller',
                        'controller' => 'Fronts\Controller\Setting',
                        'action' => 'about',
                    ),
                ),
            ),
            'Map' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/ban-do.html',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Fronts\Controller',
                        'controller' => 'Fronts\Controller\Setting',
                        'action' => 'maps',
                    ),
                ),
            ),
            'Huongdan' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/huong-dan.html',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Fronts\Controller',
                        'controller' => 'Fronts\Controller\Setting',
                        'action' => 'huongdan',
                    ),
                ),
            ),
            'Dieukhoan' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/dieu-khoan-giao-dich.html',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Fronts\Controller',
                        'controller' => 'Fronts\Controller\Setting',
                        'action' => 'dieukhoan',
                    ),
                ),
            ),
            'Sitemap' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/sitemap.html',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Fronts\Controller',
                        'controller' => 'Fronts\Controller\Setting',
                        'action' => 'sitemap',
                    ),
                ),
            ),
			
	//---------------------------------
	'EmailMaketing' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/email-maketing/send.html',
                  
                    'defaults' => array(
                       '__NAMESPACE__' => 'Fronts\Controller',
                        'controller' => 'Fronts\Controller\Shoppingcart',
                        'action' => 'EmailMaketing',
                    ),
                ),
            ),		
            
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'home'           => __DIR__ . '/../view/layout/home.phtml',
             'layoutitem'           => __DIR__ . '/../view/layout/layoutitem.phtml',
            'layoutshopping'           => __DIR__ . '/../view/layout/layoutshopping.phtml',
            //'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'layout/layout' => __DIR__ . '/../view/layout/layout404.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
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
