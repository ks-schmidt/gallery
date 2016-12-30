<?php

namespace Gallery\App;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use Gallery\App\Helper\Thumbnail;
use Slim\Exception\NotFoundException;
use Whoops\Exception\ErrorException;

class Gallery
{
    protected $c;

    public function __construct($c)
    {
        $this->c = $c;
    }


    public function handle(Request $request, Response $response, $args)
    {
        $root = $this->getWithTragetPath(
            $request->getAttribute('path', '')
        );

        if (!is_dir($root)) {
            throw new NotFoundException($request, $response);
        }

        // FOLDERS & FILES
        $prevDirectory = null;
        $idx = 0;

        $folders = $files = $preview = [];
        foreach (new \DirectoryIterator($root) as $fileInfo) {
            if ($fileInfo->isDot()) {
                continue;
            }

            if ($fileInfo->isDir()) {
                if (!preg_match('/^@./i', $fileInfo->getBasename())) {
                    $path = $this->getRelativePath($fileInfo->getRealPath());

                    $folders[] = [
                        "name"      => htmlentities($fileInfo->getFilename()),
                        "files"     => 000,
                        "thumbnail" => $this->getRelativePath(Thumbnail::getFirstOfPath($this->getWithTragetPath($path))),
                        "path"      => $path,
                    ];
                }
            }

            if ($fileInfo->isFile()) {
                if (preg_match('/.*.tn\.jpe?g$/i', $fileInfo->getBasename())) {

                    $idx++;
                    if ($prevDirectory != $fileInfo->getPath()) {
                        $idx = 1;
                    }

                    $fThumb = $this->getRelativePath($fileInfo->getRealPath());
                    $fPreview = str_replace('.tn.', '.pv.', $fThumb);
                    $fDownload = str_replace('.pv.', '.dl.', $fPreview);

                    $files[] = [
                        "idx" => sprintf("%03d", $idx),

                        "download" => $fDownload,
                        "preview"  => $fPreview,
                        "thumb"    => $fThumb,

                        "title"       => basename($fileInfo->getBasename('.tn.' . $fileInfo->getExtension())),
                        "description" => "description",
                    ];

                    $prevDirectory = $fileInfo->getPath();
                }
            }
        }

        return $this->c->view->render(
            $response, "gallery/index.phtml", [
                'path' => "/storage",

                "root" => $this->getRelativePath($root),
                'up'   => $this->getRelativePath(substr($root, 0, strrpos(rtrim($root, '/'), '/'))),

                'folders' => $folders,
                'files'   => $files,
            ]
        );
    }


    protected function getWithSourcePath($path)
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

    protected function getWithTragetPath($path)
    {
        $target = $this->c->get('settings')['converter']['target'];

        $tPath = $target['path'];
        if (!empty($path)) {
            return sprintf(
                '%s/%s', $tPath, $path
            );
        }

        return $target['path'];
    }

    protected function getRelativePath($path)
    {
        $pathArray = explode('/', $path);

        $sPathArray = explode('/', $this->c->get('settings')['converter']['source']['path']);
        $tPathArray = explode('/', $this->c->get('settings')['converter']['target']['path']);

        $result = rtrim('/' . join('/', array_diff($pathArray, $sPathArray, $tPathArray)), '/');
        if (empty($result)) {
            $result = '/';
        }

        return $result;
    }
}
