<?php
defined('BASEPATH') OR define('BASEPATH', __DIR__ . '/../');
defined('APP_MODE') OR define('APP_MODE', 'dev');

require_once BASEPATH . 'app/Bootstrap.php';

switch(APP_MODE) {
    case 'prod':
        define('APP_PROD', TRUE);
        define('APP_DEV', FALSE);
        break;
    case 'dev':
        define('APP_PROD', FALSE);
        define('APP_DEV', TRUE);
}

try {
    $bootstrap = new Bootstrap();
    $bootstrap->run();
} catch (\Exception $exc) {
    echo $exc->getMessage();
}
