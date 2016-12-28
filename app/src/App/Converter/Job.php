<?php

namespace Gallery\App\Converter;

use Gregwar\Image\Image;
use Gallery\App\Helper\Path;

class Job
{
    protected $c;

    public function __construct($c)
    {
        $this->c = $c;
    }

    protected $commands = [
        'download' => \Gallery\App\Converter\Job\Download::class,
        'preview'  => \Gallery\App\Converter\Job\Preview::class,
        'thumb'    => \Gallery\App\Converter\Job\Thumb::class,
    ];

    public function run()
    {
        $helper = new Path($this->c);

        $iSource = $this->c->get('settings')["converter"]["source"];
        $iTarget = $this->c->get('settings')["converter"]["target"];

        $it = new \RegexIterator(new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($iSource['path'], \FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST
        ), $iSource['type'], \RegexIterator::MATCH
        );

        foreach ($it as $fileInfo) {

            $directory = $helper->getWithTragetPath($helper->getRelativePath($fileInfo->getPath()));
            if ($helper->createDirectory($directory)) {
                $image = Image::open($fileInfo->getRealPath());

                foreach ($this->commands as $command) {
                    $command::convert($image)->save(
                        preg_replace('/[\/]+/', '/', sprintf("%s/%s", $directory, $command::getFile($fileInfo)))
                    );
                }

                echo sprintf("%s\n", $fileInfo->getRealPath());
                unset($image);
            }
        }
    }
}
