<?php

use Silex\Provider\MonologServiceProvider;
use Silex\Provider\WebProfilerServiceProvider;
use Symfony\Component\Debug\Debug;

// include the prod configuration
require __DIR__.'/prod.php';

if (isset($_SERVER['HTTP_CLIENT_IP'])
    || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
    || !in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', 'fe80::1', '::1'))
) {
    header('HTTP/1.0 403 Forbidden');
    exit('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
}

Debug::enable();

// enable the debug mode
$app['debug'] = TRUE;

$app->register(new MonologServiceProvider(), array(
    'monolog.logfile' => BASEPATH . 'var/logs/silex_dev.log',
));

$app->register(new WebProfilerServiceProvider(), array(
    'profiler.cache_dir' => BASEPATH . 'var/cache/profiler',
));
