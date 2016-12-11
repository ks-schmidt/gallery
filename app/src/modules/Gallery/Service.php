<?php

namespace Gallery\Gallery;

class Service extends \Gallery\Service
{
    public function getNavigation()
    {
        $result = [];

        $path = explode('/', $this->_request->getUri()->getPath());
        foreach ($path as $folder) {
            $result[implode( array_merge($result, [$folder]) )] = $folder;
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
            "00.jpg"
        ];
    }
}
