<?php

namespace Gallery\Common;

use Slim;

abstract class Controller
{
    public abstract static function registerRoutes(Slim\App $app);
}
