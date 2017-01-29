<?php

namespace Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ErrorController extends MainController
{
    public static $layout = 'error';

    public static function iniRoutes(Application &$app)
    {
        $app->error(function (\Exception $e, Request $request, $code) use ($app) {
            if ($app['debug']) {
                return;
            }

            $templates = array(
                self::$layout.$code.'.twig',
                self::$layout.substr($code, 0, 2).'x.twig',
                self::$layout.substr($code, 0, 1).'xx.twig',
                self::$layout.'/default.twig',
            );

            return new Response($app['twig']
                ->resolveTemplate($templates)
                ->render([
                    'code' => $code,
                    'layout' => self::$layout
                ]), $code);
        });
    }
}