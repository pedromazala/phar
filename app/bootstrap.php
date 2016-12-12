<?php

/**
 * @return string
 */
function path()
{
    $pieces = [];
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

    return str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $path);
}