<?php
/**
 * Created by PhpStorm.
 * Developer : pedro
 * Project   : phar
 * Date      : 07/03/16
 * Time      : 16:43
 */

namespace phar\error;


class NonDirectoryException extends \Exception
{
    public function __construct()
    {
        parent::__construct("Informed path is not a directory.");
    }
}