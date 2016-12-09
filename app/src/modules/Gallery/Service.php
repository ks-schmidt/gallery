<?php

namespace Gallery\Gallery;

class Service extends \Gallery\Service
{
    public function getNavigation()
    {
        return $this->_request->getUri()->getPath();
    }

    public function getFolders()
    {
        return "folders";
    }

    public function getFiles()
    {
        return "files";
    }
}
