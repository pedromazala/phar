<?php

namespace PharCreator;

use PharCreator\Run\PharIgnore;
use Phar;
use FilesystemIterator;

/**
 * Class App
 * @package PharCreator
 */
class App
{
    /**
     * @param array $parameters
     */
    public static function start(array $parameters)
    {
        $parameters = self::parameters($parameters);

        $root = $parameters['-r'];

        $output = $parameters['-o'];
        if (file_exists($output)) {
            unlink($output);
        }
        $dist = dirname($output);
        if (!file_exists($dist) && !is_dir($dist)) {
            mkdir($dist, 0755, true);
        }

        if ($output{0} === '.') {
            $output = path($root, substr($output, 2));
        }

        $phar = new Phar($output, (FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::KEY_AS_FILENAME));

        $files = PharIgnore::filter($root);

        $verbose = $parameters['-v'] && $parameters['-v'] !== 'false';

        echo '[' . $root . '] => [' . $output . ']' . PHP_EOL;
        foreach ($files as $file) {
            if ($verbose) {
                echo ' => ' . $file . PHP_EOL;
            }

            $path_file = path($root, $file);
            if ($parameters['-c']) {
                $path_file = self::clearFile($root, $file, $output);
            }

            $phar->addFile($path_file, $file);
        }
        $phar->setStub($phar->createDefaultStub($parameters['-s']));
    }

    /**
     * @param $options
     * @return array
     */
    private static function parameters($options)
    {
        $default = [
            '-o' => __PHAR_DIR__ . '/dist/MyFile.phar', // output of compacted file
            '-r' => __PHAR_DIR__, // root src with recursive files to compact
            '-s' => 'index.php', // stub file into root to config the start to phar
            '-v' => false,
            '-c' => false
        ];

        $parameters = [];
        foreach ($options as $arg) {
            $in = explode("=", $arg);
            if (count($in) == 2) {
                $parameters[$in[0]] = $in[1];
            } else {
                $parameters[$in[0]] = 0;
            }
        }

        return array_replace($default, $parameters);
    }

    /**
     * Remove os comentários dos arquivos
     * @param $root
     * @param $file
     * @param $output
     * @return string
     */
    private static function clearFile($root, $file, $output)
    {
        $file_contents = file_get_contents(path($root, $file));
        $file_new = '';

        $commentTokens = [T_COMMENT];
        if (defined('T_DOC_COMMENT'))
            $commentTokens[] = T_DOC_COMMENT; // PHP 5
        if (defined('T_ML_COMMENT'))
            $commentTokens[] = T_ML_COMMENT;  // PHP 4

        $tokens = token_get_all($file_contents);

        foreach ($tokens as $token) {
            if (is_array($token)) {
                if (in_array($token[0], $commentTokens)) {
                    continue;
                }

                $token = $token[1];
            }
            $file_new .= $token;
        }

        $temp_file = path(dirname($output), 'temp', $file);
        $temp_dir = dirname($temp_file);
        if (!file_exists($temp_dir)) {
            mkdir($temp_dir, 0777, true);
        }

        file_put_contents($temp_file, $file_new);

        return $temp_file;
    }
}
