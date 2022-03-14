<?php

return array(
    'modules' => array(
       'User',
       'Fronts',
       'News',
       'Product',
       'Slideshow',
       'Banner',
       'Customer',
       'Contact',
        'Setting',
        'Invoice',
    ),
    'module_listener_options' => array(
        'module_paths' => array(
            './module',
            './vendor',
        ),
        'config_glob_paths' => array(
            'config/autoload/{,*.}{global,local}.php',
        ),
    ),
);
