<?php

namespace Gallery\App\Helper;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class Path
{
    protected $c;

    public function __construct($c)
    {
        $this->c = $c;
    }

    public function getWithSourcePath($path)
    {
        $source = $this->c->get('settings')['converter']['source'];

        $sPath = $source['path'];
        if (!empty($path)) {
            return sprintf(
                '%s/%s', $sPath, $path
            );
        }

        return $source['path'];
    }

    public function getWithTragetPath($path)
    {
        $target = $this->c->get('settings')['converter']['target'];

        $tPath = $target['path'];
        if (!empty($path)) {
            return sprintf(
                '%s/%s', $tPath, ltrim($path, '/')
            );
        }

        return $target['path'];
    }

    public function getRelativePath($path)
    {
        $pathArray = explode('/', $path);

        $sPathArray = explode('/', $this->c->get('settings')['converter']['source']['path']);
        $tPathArray = explode('/', $this->c->get('settings')['converter']['target']['path']);

        return rtrim('/' . join('/', array_diff($pathArray, $sPathArray, $tPathArray)), '/');
    }

    protected static $paths = [];

    public function createDirectory($directory)
    {
        if (!isset($paths[ $directory ])) {
            if (!is_dir($directory)) {
                $paths[ $directory ] = true;

                return mkdir($directory, 0777, true);
            }
        }

        return true;
    }
}
