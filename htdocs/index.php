<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

/** Blackflag_Application (caches config) */
require_once 'Blackflag/Application.php';

// Create application, bootstrap, and run
$application = new Blackflag_Application(
    APPLICATION_ENV,
	array(
		'configFile' => APPLICATION_PATH . '/configs/application.xml'
	)
);
$application->bootstrap()->run();