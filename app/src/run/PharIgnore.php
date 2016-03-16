<?php
/**
 * Created by PhpStorm.
 * Developer : pedro
 * Project   : migration.fagoc.br
 * Date      : 07/03/16
 * Time      : 16:47
 */

namespace phar\run;


class PharIgnore
{

    private static $pharignoreFiles = array(
        '.git/*' => false,
        '.idea/*' => false,
        '*.pharignore' => false
    );

    /**
     * @param string $rootPath
     * @param string[] $files
     *
     * @return string[]
     */
    public static function filter($rootPath, $files)
    {
        self::init($rootPath, $files);

        foreach ($files as $key => $file) {
            $include = true;

            foreach (self::$pharignoreFiles as $pattern => $add) {
                $found = fnmatch($pattern, $file);
                if ($found) {
                    if (!$add) {
                        $include = false;
                    } else {
                        $include = true;
                    }
                }
            }

            if (!$include) {
                unset($files[$key]);
            }
        }

        return $files;
    }

    /**
     * @param string $rootPath
     * @param string[] $files
     */
    private static function init($rootPath, $files)
    {
        foreach ($files as $file) {
            $path = dirname($file);

            $pharignorePath = path($rootPath, $path, '.pharignore');
            if (file_exists($pharignorePath)) {
                $lines = explode("\n", file_get_contents($pharignorePath));
                foreach ($lines as $line) {
                    if (strlen(trim($line)) > 0) {
                        $include = false;
                        if ($line{0} == '!') {
                            $include = true;
                            $line = substr($line, 1);
                        }

                        $pathIgnore = path($path, $line);
                        if ($path === '.') {
                            $pathIgnore = $line;
                        }
                        self::$pharignoreFiles[$pathIgnore] = $include;
                    }
                }
            }
        }
    }
}