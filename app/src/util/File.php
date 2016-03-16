<?php
/**
 * Created by PhpStorm.
 * Developer : pedro
 * Project   : phar
 * Date      : 07/03/16
 * Time      : 16:45
 */

namespace phar\util;

use \phar\error\NonDirectoryException;

class File
{
    private static $ignoredPaths = array(
        '.',
        '..',
    );

    public static function getAllFiles($dir)
    {
        if (!is_dir($dir)) {
            throw new NonDirectoryException();
        }

        $files = self::openDirectory($dir);

        return $files;
    }

    private static function openDirectory($rootPath, $insides = array())
    {
        $files = array();

        $startPath = path($rootPath, $insides);
        $openedDir = opendir($startPath);
        if ($openedDir) {
            while (($path = readdir($openedDir)) !== false) {
                if (in_array($path, self::$ignoredPaths)) {
                    continue;
                }

                $fullPath = path($startPath, $path);

                if (is_dir($fullPath)) {
                    $files = array_merge($files, self::openDirectory($rootPath, array_merge($insides, array($path))));
                } else {
                    $files[] = path($insides, $path);
                }
            }
            closedir($openedDir);
        }

        return $files;
    }
}