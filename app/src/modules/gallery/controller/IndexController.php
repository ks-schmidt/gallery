<?php

namespace Gallery;

use Slim;
use Gallery\Common\Controller;

class IndexController extends Controller
{
    private static $instance;

    private function __construct() {}
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new IndexController();
        }

        return self::$instance;
    }

    public static function registerRoutes(Slim\App $app)
    {
        $app->get('/', function (Request $request, Response $response) use ($app) {
            $controller = self::getInstance();

            $response = $app->view->render($response, "gallery/index.phtml", ['content' => $controller->index()]);
            return $response;

        });
    }


    // ACTIONS

    public function index()
    {
        return "index controller was called";
    }
}
