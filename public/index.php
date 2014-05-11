<?php
// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/application'));

// Define application environment
if (!defined('APPLICATION_ENV')) {
    $env = getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production';
    if ($env == 'production') {
        if ($_COOKIE['proddebug'] || $_GET['proddebug'] == true) {
            setcookie('proddebug', true);
            $env = 'proddebug';
        }
    }
    define('APPLICATION_ENV', $env);
}


// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/library'),
    get_include_path(),
)));

define('GALLERY_PATH', realpath(APPLICATION_PATH . '/../img/gallery'));

/* Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);

$application->bootstrap()
            ->run();