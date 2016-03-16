#!/usr/bin/env php
​<?php
define('__PHAR_DIR__', __DIR__);

require __PHAR_DIR__ . "/app/autoload.php";

//$srcRoot = "/home/pedro/NetBeansProjects/git/migration.fagoc.br";
//$srcRoot = "/home/pedromazala/NetBeansProjects/migration.fagoc.br";
\phar\run\Run::start($argv);