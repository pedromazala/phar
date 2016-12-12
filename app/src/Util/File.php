<?php

namespace PharCreator\Util;

use PharCreator\Error\NonDirectoryException;

/**
 * Class File
 * @package PharCreator\Util
 */
class File
{
    /**
     * @var array
     */
    private static $ignoredPaths = ['.', '..'];

    /**
     * @param $dir
     * @return array
     * @throws NonDirectoryException
     */
    public static function getAllFiles($dir)
    {
        if (!is_dir($dir)) {
            throw new NonDirectoryException();
        }

        $files = self::openDirectory($dir);

        return $files;
    }

    /**
     * @param $rootPath
     * @param array $insides
     * @return array
     */
    private static function openDirectory($rootPath, $insides = [])
    {
        $files = [];

        $startPath = path($rootPath, $insides);
        $openedDir = opendir($startPath);
        if ($openedDir) {
            while (($path = readdir($openedDir)) !== false) {
                if (in_array($path, self::$ignoredPaths)) {
                    continue;
                }

                $fullPath = path($startPath, $path);

                if (is_dir($fullPath)) {
                    $files = array_merge($files, self::openDirectory($rootPath, array_merge($insides, [$path])));
                } else {
                    $files[] = path($insides, $path);
                }
            }
            closedir($openedDir);
        }

        return $files;
    }
}