<?php

error_reporting(E_ALL);
require_once('aws_config.php');

// DB 
define('DB_DRIVER', 'pdo_mysql');
define('DB_HOST', 'localhost');
define('DB_USER', 'username');
define('DB_PASS', 'password');
define('DB_NAME', 'schemaname');
define('DB_PORT', 3306);

// THIS WILL OUTPUT SQL AND ARGS TO error_log()
//define('SQL_DEBUG', 1); 

// UNCOMMENT THIS TO LOG DFP API RAW OUTPUT 
define('DFP_LOG', '/tmp');

// PATHS 
define('BASE_DIR',   dirname(__DIR__));
define('CONFIG_DIR', __DIR__);
define('LIB_DIR',    BASE_DIR.'/lib');
define('APP_DIR',    BASE_DIR.'/app');
define('FILES_DIR',  BASE_DIR.'/files');


// SETTINGS 
define('GOOGLE_DFP_API_VERSION', 'v201611');

// DEV DEFAULT - UNIFIED NETWORK ID 
define('DFP_NETWORK_ID', '123123123123');







