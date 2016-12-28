<?php

namespace Gallery\App\Converter;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use Gregwar\Image\Image;
use Gallery\App\Helper\Path;

class Job
{
    protected $c;

    public function __construct($c)
    {
        $this->c = $c;
    }


    public function run()
    {
        $sPath = $this->c->get('settings')["converter"]["source"]["path"];
        $tPath = $this->c->get('settings')["converter"]["target"]["path"];

        $it = new \RegexIterator(new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($sPath, \FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST
            ), '/.*\.jpe?g$/i', \RegexIterator::MATCH
        );

        $helper = new Path($this->c);
        foreach ($it as $fileInfo) {
            $tRealPath = $helper->getWithTragetPath($helper->getRelativePath($fileInfo->getPath()));
            if (!is_dir($tRealPath)) {
                mkdir($tRealPath, 0777, true);
            }

            $file = sprintf("%s/%s", $tRealPath, $this->transform($fileInfo));

            $image = $this->convert($fileInfo->getRealPath(), 188, 188);
            $image->save($file);

            echo sprintf("%s\n", $file);
        }
    }

    protected function convert($file, $tWidth, $tHeight)
    {
        return Image::open($file)->zoomCrop($tWidth, $tHeight);
    }

    protected function transform($file)
    {
        return sprintf(
            '%s_tn.%s', basename($file->getBasename('.'.$file->getExtension())), $file->getExtension()
        );
    }
}
