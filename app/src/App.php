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
            $phar->addFile(path($root, $file), $file);
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
            '-v' => false
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
}