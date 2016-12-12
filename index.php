#!/usr/bin/env php
​<?php

define('__PHAR_DIR__', __DIR__);

require __DIR__ . "/vendor/autoload.php";

use PharCreator\App;

App::start($argv);