<?php

namespace Gallery\Gallery;

class Service extends \Gallery\Service
{
    public function getNavigation()
    {
        $result = [];

        $path = explode('/', 'a/b/c/d/e/f/g/h/i/j/k/l/m/n/o/p/q/r/s/t/u/v/w/x/y/z/a/b/c/d/e/f/g/h/i/j/k/l/m/n/o/p/q/r/s/t/u/v/w/x/y/z/a/b/c/d/e/f/g/h/i/j/k/l/m/n/o/p/q/r/s/t/u/v/w/x/y/z');
        foreach ($path as $folder) {
            $result[implode( '/', array_merge($result, [$folder]) )] = $folder;
        }

        return $result;
    }

    public function getFolders()
    {
        return "folders";
    }

    public function getFiles()
    {
        return [
            "00.jpg",
            "01.jpg",
            "02.jpg",
            "03.jpg",
            "04.jpg",
            "05.jpg",
            "06.jpg"
        ];
    }
}
