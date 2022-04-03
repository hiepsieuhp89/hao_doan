<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
dirname(__DIR__);
//inforurl
$host = ':8000/giadung';
define('WEB_MEDIA',  str_replace("\\","/",__DIR__));
define('WEB_PATH', 'http://'.$_SERVER['SERVER_NAME'].$host);
define('WEB_STATIC', 'http://'.$_SERVER['SERVER_NAME'].$host.'/static');
define('WEB_IMG', 'http://'.$_SERVER['SERVER_NAME'].$host.'/media/');
define('WEB_PUBLIC', 'http://'.$_SERVER['SERVER_NAME'].$host.'/public');

defined('APPLICATION_PATH') ||
        define('APPLICATION_PATH', __DIR__);
define("URL_UPLOAD", "/public");
if (php_sapi_name() === 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
    return false;
}
require 'init_autoloader.php';
Zend\Mvc\Application::init(require 'config/application.config.php')->run();
