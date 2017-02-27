<?php

namespace Gallery\App\Converter;

class FileIterator implements \Iterator, \OuterIterator
{
    protected $it;

    public function __construct($path, $filter = '.*')
    {
        $this->it = new \RegexIterator(
            new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST
            ),
            $filter, \RegexIterator::MATCH
        );
    }

    /**
     * Returns the inner iterator for the current entry.
     *
     * @link http://php.net/manual/en/outeriterator.getinneriterator.php
     * @return Iterator The inner iterator for the current entry.
     * @since 5.1.0
     */
    public function getInnerIterator()
    {
        return $this->it;
    }

    /**
     * Return the current element
     *
     * @link http://php.net/manual/en/iterator.current.php
     * @return File Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        return new File(
            $this->getInnerIterator()->current()
        );
    }

    /**
     * Move forward to next element
     *
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        return $this->getInnerIterator()->next();
    }

    /**
     * Return the key of the current element
     *
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return $this->getInnerIterator()->key();
    }

    /**
     * Checks if current position is valid
     *
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        return $this->getInnerIterator()->valid();
    }

    /**
     * Rewind the Iterator to the first element
     *
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        return $this->getInnerIterator()->rewind();
    }
}
