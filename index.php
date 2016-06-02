<?php
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
//chdir(dirname(__DIR__));
//sửa bỏ public

chdir(__DIR__);
//dinh nghia path dir de su dung khi load layout
//define('PATH_APP', dirname(__DIR__).'/public');
define('SITE_URL', 'http://localhost/zend2/');

define('PATH_APP', __DIR__);
define('PATH_LAYOUT', __DIR__.'/public');
define('PATH_PUBLIC',  SITE_URL.'/public');
// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
    return false;
}

// Setup autoloading
require 'init_autoloader.php';

// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();
