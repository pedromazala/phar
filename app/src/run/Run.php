<?php
/**
 * Created by PhpStorm.
 * Developer : pedro
 * Project   : migration.fagoc.br
 * Date      : 07/03/16
 * Time      : 16:47
 */

namespace phar\run;

use \phar\util\File;

class Run
{
    /**
     * @param array() $parameters
     */
    public static function start($parameters)
    {
        $parameters = self::configParameters($parameters);

        $srcRoot = $parameters['[root]'];

        $buildRoot = path($srcRoot, $parameters['[dist]']);
        if (!file_exists($buildRoot)) {
            mkdir($buildRoot, 0777, true);
        }

        $phar = new \Phar(
            path($buildRoot, $parameters['[phar]']) . '.phar',
            (\FilesystemIterator::CURRENT_AS_FILEINFO | \FilesystemIterator::KEY_AS_FILENAME),
            $parameters['[phar]'] . '.phar'
        );

        $files = PharIgnore::filter($srcRoot, File::getAllFiles($srcRoot));

        foreach ($files as $file) {
            if ($parameters['-v']) {
                print "added: " . $file . " [" . path($srcRoot, $file) . "]" . PHP_EOL;
            }
            $phar->addFile(path($srcRoot, $file), $file);
        }
        $phar->setStub($phar->createDefaultStub($parameters['[stub]']));

        #copy($srcRoot . "/config.ini", $buildRoot . "/config.ini");
    }

    private static function configParameters($parameters)
    {
        $toSet = array(
            '[root]',
            '[phar]',
            '[stub]',
            '[dist]',
        );
        $params = array(
            '[root]' => './',
            '[phar]' => 'phar',
            '[stub]' => 'index.php',
            '[dist]' => 'dist',
            '-v' => true,
        );

        $configuredParams = array();
        array_shift($parameters);
        for ($i = 0; $i < count($parameters); $i++) {
            $p = $parameters[$i];

            if (in_array($p, array_keys($params))) {
                $value = $params[$p];
                if (isset($parameters[$i + 1]) && !in_array($parameters[$i + 1], array_keys($params))) {
                    $value = $parameters[$i + 1];
                    $i++;
                }
                $configuredParams[$p] = $value;
            } else {
                foreach ($toSet as $set) {
                    if (!isset($configuredParams[$set])) {
                        $configuredParams[$set] = $p;
                        break;
                    }
                }
            }
        }

        $params['-v'] = false;
        if (!isset($configuredParams['[phar]']) && (isset($configuredParams['[root]']) && strlen($configuredParams['[root]']) > 3)) {
            $exploded = explode(DIRECTORY_SEPARATOR, $configuredParams['[root]']);
            $configuredParams['[phar]'] = array_pop($exploded);
        }

        return (array_merge($params, $configuredParams));
    }
}