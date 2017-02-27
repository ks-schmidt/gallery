<?php

namespace Gallery\App\Converter;

use Gallery\App\Configuration;

class File
{
    CONST ROOT = '/';

    /**
     * @var \SplFileInfo
     */
    protected $realPath;

    /**
     * @param $file
     */
    public function __construct($realPath)
    {
        $this->realPath = $realPath;
        if ($realPath instanceof \SplFileInfo) {
            $this->realPath = $realPath->getRealPath();
        }
    }

    /**
     * @return File
     */
    public function getRelativePath()
    {
        $path = $this->realPath;
        if ($this->isFile()) {
            $path = $this->getPath()->toString();
        }

        return new File(
            self::normalize(
                str_replace(
                    [
                        Configuration::get('settings.converter.source.path'),
                        Configuration::get('settings.converter.target.path'),
                    ],
                    '',
                    $path
                )
            )
        );
    }

    /**
     * @return string
     */
    public function getRelativeFile()
    {
        if ($this->isFile()) {
            return new File(
                self::normalize(
                    str_replace(
                        [
                            Configuration::get('settings.converter.source.path'),
                            Configuration::get('settings.converter.target.path'),
                        ],
                        '',
                        $this->realPath
                    )
                )
            );
        }

        return '';
    }

    /**
     * @return File
     */
    public function getParent($recursive = false)
    {
        $parent = [];

        $path = array_filter(explode('/', $this->getPath()->toString()));
        while (!empty($path)) {
            $parent[] = self::normalize(
                implode('/', $path)
            );

            $path = array_slice($path, 0, count($path) - 1);
            if ($recursive && !empty($path)) {
                continue;
            }

            $parent[] = self::ROOT;
            break;
        }

        array_walk($parent, function (& $value) {
            $value = new File($value);
        });

        return $recursive ? $parent : array_shift($parent);
    }

    /**
     * @return mixed
     */
    public function convertToSourcePath()
    {
        return new File(
            self::normalize(
                sprintf('%s/%s', Configuration::get('settings.converter.source.path'), $this->getRelativePath()->toString())
            )
        );
    }

    /**
     * @return mixed
     */
    public function convertToSourceFile()
    {
        return new File(
            self::normalize(
                sprintf('%s/%s', Configuration::get('settings.converter.source.path'), $this->getRelativeFile()->toString())
            )
        );
    }

    /**
     * @return mixed
     */
    public function convertToTargetPath()
    {
        return new File(
            self::normalize(
                sprintf('%s/%s', Configuration::get('settings.converter.target.path'), $this->getRelativePath()->toString())
            )
        );
    }

    /**
     * @return File
     */
    public function convertToTargetFile($filename = null, $suffix = null)
    {
        $filename = $filename ?: $this->getBasename($this->getExtension());
        if ($suffix) {
            $filename = strtolower(
                sprintf(
                    '%s.%s.%s', $filename, $suffix, $this->getExtension()
                )
            );
        }

        return new File(
            self::normalize(
                sprintf(
                    '%s/%s/%s', Configuration::get('settings.converter.target.path'), $this->getRelativePath()->toString(), $filename
                )
            )
        );
    }

    public function getPath()
    {
        $path = $this->toString();
        if ($this->isFile()) {
            $path = str_replace($this->getBasename(), '', $path);
        }

        return new File(
            self::normalize($path)
        );
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->realPath;
    }

    /**
     * @param null $suffix
     *
     * @return string
     */
    public function getBasename($suffix = null)
    {
        $info = pathinfo($this->realPath);

        return isset($info['basename']) ? str_replace($suffix, '', $info['basename']) : null;
    }

    /**
     * @return string
     */
    public function getExtension()
    {
        $info = pathinfo($this->realPath);

        return isset($info['extension']) ? $info['extension'] : null;
    }

    /**
     * @param int $precision
     *
     * @return string
     */
    public function getSize()
    {
        return $this->splFileInfo->getSize();
    }

    /**
     * @param int $precision
     *
     * @return string
     */
    public function getSizeWithUnit($precision = 2)
    {
        return $this->getBytesAsString(
            $this->splFileInfo->getSize(), $precision
        );
    }

    /**
     * @param $size
     * @param int $precision
     *
     * @return string
     */
    protected function getBytesAsString($size, $precision = 2, $addUnit = true)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $power = $size > 0 ? floor(log($size, 1024)) : 0;

        return number_format($size / pow(1024, $power), $precision, '.', ',') . ($addUnit ? $units[ $power ] : '');
    }

    /**
     * @param $path
     *
     * @return mixed
     */
    public static function normalize($path)
    {
        $patterns = ['~/{2,}~', '~/(\./)+~', '~([^/\.]+/(?R)*\.{2,}/)~', '~\.\./~'];
        $replacements = ['/', '/', '', ''];

        return rtrim(
            preg_replace($patterns, $replacements, $path), '/'
        );
    }

    /**
     * @param $directory
     *
     * @return bool
     */
    public static function createDirectory($directory)
    {
        if (!is_dir($directory)) {
            return mkdir($directory, 0777, true);
        }

        return true;
    }

    /**
     * @return bool
     */
    protected function isFile()
    {
        $info = pathinfo($this->realPath);
        if (array_key_exists('extension', $info)) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    protected function isDir()
    {
        $info = pathinfo($this->realPath);
        if (array_key_exists('extension', $info)) {
            return false;
        }

        return true;
    }
}
