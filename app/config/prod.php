<?php
ini_set('display_errors', 0);

$app['twig.path'] = [BASEPATH . 'app/Views'];
$app['twig.options'] = [
    'cache' => BASEPATH . 'var/cache/twig'
];
