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

    protected $jobs = [
        //'download' => \Gallery\App\Converter\Job\Download::class,
        'preview'  => \Gallery\App\Converter\Job\Preview::class,
        //'thumb'    => \Gallery\App\Converter\Job\Thumb::class,
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
                $image = Image::open($fileInfo->getRealPath())
                    ->rotate(array_values([0, 0, 0, 180, 0, 0, -90, 0, 90])[@exif_read_data($fileInfo->getRealPath())['Orientation'] ?: 0]);

                foreach ($this->jobs as $job) {
                    $job::convert($image)->save(
                        preg_replace('/[\/]+/', '/', sprintf("%s/%s", $directory, $this->getFile($fileInfo, null, $job::SUFFIX)))
                    );
                }

                echo sprintf("%s\n", $fileInfo->getRealPath());
                unset($image);
            }
        }
    }

    public function getFile($fileInfo, $altName = null, $suffix = null)
    {
        $filename = basename($fileInfo->getBasename('.' . $fileInfo->getExtension()));
        if ($altName) {
            $filename = $altName;
        }

        $extension = strtolower($fileInfo->getExtension());
        return sprintf(
            '%s.%s.%s', $filename, $suffix, $extension
        );
    }
}
