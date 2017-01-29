<?php

namespace Controllers;

use Models\GuestsModel;
use Models\LikesModel;
use Models\RecallsModel;
use Silex\Application;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SiteController extends MainController
{
    public static $layout = 'site';

    public static function iniRoutes(Application &$app) {
        $self = new self();

        $route_name = 'homepage';
        $app->get('/', function () use ($app, $self, $route_name){
            return $self->actionIndex($app, $route_name);
        })->bind($route_name);

        $route_name = 'create_recall';
        $app->match("/$route_name", function () use ($app, $self, $route_name){
            return $self->actionCreate($app, $route_name);
        })->bind($route_name)->method('GET|POST');

        $route_name = 'view_recall';
        $app->get("/$route_name/{id}", function ($id) use ($app, $self, $route_name){
            return $self->actionView($app, $route_name, [
                'id' => $id
            ]);
        })->bind($route_name);

        $route_name = 'like_recall';
        $app->post("/$route_name", function () use ($app, $self, $route_name){
            return $self->actionLike($app, $route_name);
        })->bind($route_name);
    }

    public function actionIndex(Application $app, $route_name, $params = [])
    {
        $recalls_model = new RecallsModel($app);
        if($recalls_model->validate_order($_GET)) {
            $order = [
                'sort' => $_GET['sort'],
                'order' => $_GET['order']
            ];
        }

        $results = isset($order) ? $recalls_model->get_all($order) : $recalls_model->get_all();

        if(!empty($results)) {
            $titles = $recalls_model->get_titles_by_results($results);
        }

        return $app['twig']->render(self::$layout . '/index.twig', [
            'layout' => self::$layout,
            'recalls' => !empty($results && $titles)? [
                'titles' => $titles,
                'items' => $results
            ] : [],
            'route' => $route_name
        ]);
    }

    public function actionCreate(Application $app, $route_name, $params = [])
    {
        $guest_model = new GuestsModel($app);
        $guest_model->ip = $_SERVER['REMOTE_ADDR'];
        $guest = $guest_model->get_by_ip();

        if(isset($_POST['text'])) {
            $recall_model = new RecallsModel($app);

            if(!empty($_POST['name'])) {
                $guest_model->id = $guest['id'];
                $guest_model->name = $_POST['name'];
                $guest_model->update();
            }

            $recall_model->text = $_POST['text'];
            $recall_model->guest_id = $guest['id'];
            $recall_model->date_create = time();

            if($recall_model->validate()) {
                if($recall_model->save()) {
                    $app['session']->getFlashBag()->add('create_recall', [
                        'success' => TRUE,
                        'message' => 'Отзыв успешно добавлен'
                    ]);
                } else {
                    $app['session']->getFlashBag()->add('create_recall', [
                        'success' => FALSE,
                        'message' => 'Произошла ошибка при добавлении отзыва! Пожалуйста, попробуйте еще раз.'
                    ]);
                }
            } else {
                $app['session']->getFlashBag()->add('create_recall', [
                    'success' => FALSE,
                    'message' => 'Не корректные данные.'
                ]);
            }

            return $app->redirect('create_recall');
        }

        $flash = $app['session']->getFlashBag()->get('create_recall');
        return $app['twig']->render(self::$layout . '/create.twig', [
            'layout' => self::$layout,
            'route' => $route_name,
            'empty_name' => empty($guest['name']),
            'alert' => !empty($flash) ? $flash[0] : FALSE
        ]);
    }

    public function actionView(Application $app, $route_name, $params = [])
    {
        $recall = new RecallsModel($app);
        $result = $recall->get_by_id($params['id']);

        if(empty($result)) {
            throw new NotFoundHttpException();
        }

        $result['next_id'] = $recall->get_next_id($result['id']);
        $result['prev_id'] = $recall->get_prev_id($result['id']);

        return $app['twig']->render(self::$layout . '/view.twig', [
            'layout' => self::$layout,
            'recall' => $result,
            'route' => $route_name
        ]);
    }

    public function actionLike(Application $app, $route_name, $params = [])
    {
        $likes = new LikesModel($app);
        $guest = new GuestsModel($app);
        $guest->ip = $_SERVER['REMOTE_ADDR'];

        $likes->recall_id = $_POST['id'];
        $likes->guest_id = $guest->get_id_by_ip();

        $result = $likes->save();

        return $app->json($result);
    }
}