<?php

namespace Controllers;

use Silex\Application;

class MainController
{
    public static $layout = 'site';

    public static function iniRoutes(Application &$app) {
        $self = new self();

        $route_name = 'homepage';
        $app->get('/', function () use ($app, $self, $route_name){
            return $self->actionIndex($app, $route_name);
        })->bind($route_name);
    }

    public function actionIndex(Application $app, $route_name, $params = []) {
        return $app['twig']->render(static::$layout . '/index.twig', [
            'layout' => static::$layout
        ]);
    }
}