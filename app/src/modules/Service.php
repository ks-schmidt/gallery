<?php

namespace Gallery;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Interop\Container\ContainerInterface;

abstract class Service
{
    /** @var \Psr\Http\Message\ServerRequestInterface $_request */
    protected $_request;

    /** @var \Psr\Http\Message\ResponseInterface $_response */
    protected $_response;

    /** @var \Interop\Container\ContainerInterface $_container */
    protected $_container;

    public function __construct(Request & $request, Response & $response, ContainerInterface & $container)
    {
        $this->_request = $request;
        $this->_response = $response;
        $this->_container = $container;
    }
}
