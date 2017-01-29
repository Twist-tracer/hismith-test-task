<?php

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Provider\AssetServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;

use Controllers\ErrorController;
use Controllers\SiteController;

class Bootstrap {
    /**
     * Application
     *
     * @var Silex\Application
     */
    protected $app;

    /**
     * Constructor
     *
     */
    public function __construct() {
        require_once BASEPATH . '/vendor/autoload.php';

        // Create app
        $app = new Silex\Application();

        // Init middlewares
        $this->_iniMiddlewares($app);

        // Init providers
        $this->_iniProviders($app);

        // Init config
        $this->_iniConfig($app);

        // Init controllers
        $this->_iniControllers($app);

        $this->app = $app;
    }


    /**
     *  Init middlewares
     *
     * @param Application $app
     */
    private function _iniMiddlewares(Application &$app) {
        // The middleware is run before the routing and the security.
        $app->before(function (Request $request, Application $app) {
            $guest = new \Models\GuestsModel($app);
            $guest->ip = $_SERVER['REMOTE_ADDR'];
            $guest->save();
        }, Application::EARLY_EVENT);

        // The middleware is run after the routing and the security.
        $app->before(function (Request $request, Application $app) {

        });

        // An after application middleware allows you to tweak the Response before it is sent to the client:
        $app->after(function (Request $request, Response $response) {

        });

        // Set event after the Response
        $app->finish(function (Request $request, Response $response) use ($app) {

        });
    }

    /**
     *  Init providers
     *
     * @param Application $app
     */
    private function _iniProviders(Application &$app) {
        $app->register(new ServiceControllerServiceProvider());
        $app->register(new Silex\Provider\SessionServiceProvider());
        $app->register(new AssetServiceProvider());
        $app->register(new TwigServiceProvider());
        $app->register(new HttpFragmentServiceProvider());
        $app['twig'] = $app->extend('twig', function ($twig, $app) {
            // add custom globals, filters, tags, ...

            return $twig;
        });

        // DoctrineService
        $app->register(
            new Silex\Provider\DoctrineServiceProvider(), [
            'dbs.options' => require BASEPATH . 'app/Config/db.php'
        ]);

        // DoctrineOrmService
        $app->register(
            new Providers\DoctrineOrmServiceProvider(), array(
            'orm.metadata' => BASEPATH . "/app/Models/ORM",
            'orm.options' => require BASEPATH . 'app/Config/db.php'
        ));

        // FormService
        $app->register(new Silex\Provider\FormServiceProvider(), []);

        // ValidatorService
        $app->register(new Silex\Provider\ValidatorServiceProvider(), []);
    }

    /**
     *  Init config
     *
     * @param Application $app
     */
    private function _iniConfig(Application &$app) {
        if(APP_PROD) {
            require BASEPATH . 'app/Config/prod.php';
        } elseif(APP_DEV) {
            require BASEPATH . 'app/Config/dev.php';
        }
    }

    /**
     *  Initialization controllers
     *
     * @param Application $app
     */
    private function _iniControllers(Application &$app) {
        SiteController::iniRoutes($app);
        ErrorController::iniRoutes($app);
    }

    /**
     *  Run this application
     */
    public function run() {
        $this->app->run();
    }

}