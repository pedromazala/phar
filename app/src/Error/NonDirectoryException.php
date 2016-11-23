<?php

namespace PharCreator\Error;

/**
 * Class NonDirectoryException
 * @package PharCreator\Error
 */
class NonDirectoryException extends \Exception
{
    /**
     * NonDirectoryException constructor.
     */
    public function __construct()
    {
        parent::__construct("Informed path is not a directory.");
    }
}