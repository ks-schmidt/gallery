<?php

namespace Gallery\controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class IndexController
{
    protected $container;
    public function __construct($c)
    {
        $this->container = $c;
    }

    public function index(Request $request, Response $response)
    {
        return $this->container['view']->render($response, "gallery/index.phtml", ['content' => 'the index action content']);
    }
}
