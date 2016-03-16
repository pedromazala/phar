<?php

function path() {
    $pieces = array();
    $funcArgs = func_get_args();
    foreach ($funcArgs as $funcArg) {
        if (is_array($funcArg)) {
            foreach ($funcArg as $arg) {
                $pieces[] = $arg;
            }
        } else {
            $pieces[] = $funcArg;
        }
    }
    $path = implode(DIRECTORY_SEPARATOR, $pieces);

    return $path;
}

/**
 * @param string $class
 */
spl_autoload_register(function ($class) {

    $parts = explode('\\', $class);
    // Removing project namespace
    array_shift($parts);

    if (count($parts) > 1) {

        $path = implode(DIRECTORY_SEPARATOR, $parts);
        $filename = path(__PHAR_DIR__, 'app', 'src', $path) . '.php';

        if (file_exists($filename)) {

            /** @noinspection PhpIncludeInspection */
            require_once $filename;
        }
    }
});