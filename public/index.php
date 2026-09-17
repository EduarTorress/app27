<?php
date_default_timezone_set('America/Lima');
require __DIR__ . "/../vendor/autoload.php";
use Core\Foundation\Application;
$rootdir = dirname(__DIR__);
$_ENV['DIR_ROOT'] = $rootdir;
$dotenv = \Dotenv\Dotenv::createImmutable($rootdir);
$dotenv->load();
$config = require $rootdir . "/config/app.php";
$app = Application::getInstance($rootdir, $config, "");
$app->empresa = 'grifomocupe';
require $rootdir . "/routes/web.php";
$app->run();
