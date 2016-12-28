<?php

namespace Gallery\App;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class Gallery
{
    protected $c;

    public function __construct($c)
    {
        $this->c = $c;
    }


    public function handle(Request $request, Response $response, $args)
    {
        $path = $this->getWithTragetPath(
            $request->getAttribute('path', '')
        );

        // BREADCRUMB
        $breadcrumb = [
            [
                "name" => 'Timeline',
                "path" => '/',
            ],
        ];

        $prevPath = [];
        foreach (explode('/', $this->getRelativePath($path)) as $folder) {
            if (empty($folder)) {
                continue;
            }

            $breadcrumb[] = [
                "name" => htmlentities($folder),
                "path" => '/' . join('/', array_merge($prevPath, [$folder])),
            ];

            $prevPath[] = $folder;
        }

        // FOLDERS & FILES
        $folders = $files = $preview = [];
        foreach (new \DirectoryIterator($path) as $fileInfo) {
            if ($fileInfo->isDot()) {
                continue;
            }

            if ($fileInfo->isDir()) {

                $folders[] = [
                    "name"      => $fileInfo->getFilename(),
                    "files"     => 000,
                    "thumbnail" => "04_tn.JPG",
                    "path"      => $this->getRelativePath($fileInfo->getRealPath()),
                ];
            }

            if ($fileInfo->isFile()) {
                if (preg_match('/.*.tn\.jpe?g$/i', $fileInfo->getBasename())) {
                    $fThumb = $this->getRelativePath($fileInfo->getRealPath());
                    $fPreview = str_replace('.tn.', '.pv.', $fThumb);
                    $fDownload = str_replace('.pv.', '.dl.', $fPreview);

                    $files[] = [
                        "idx" => "idx",

                        "download" => $fDownload,
                        "preview"  => $fPreview,
                        "thumb"    => $fThumb,

                        "title"       => $fileInfo->getBasename(),
                        "description" => "description",
                    ];
                }
            }
        }

        foreach ($folders as & $folder) {
            foreach (new \DirectoryIterator($this->getWithTragetPath($folder["path"])) as $fileInfo) {
                if (preg_match('/.*\.jpe?g$/i', $fileInfo->getBasename())) {
                    $folder["thumbnail"] = $this->getRelativePath($fileInfo->getRealPath());
                    break;
                }
            }
        }

        return $this->c->view->render(
            $response, "gallery/index.phtml", [
                'path' => "/storage/",

                'breadcrumb' => $breadcrumb,
                'folders'    => $folders,
                'files'      => $files,
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

        return rtrim('/' . join('/', array_diff($pathArray, $sPathArray, $tPathArray)), '/');
    }
}
