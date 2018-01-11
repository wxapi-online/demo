<?php
ini_set('error_reporting', -1);
ini_set('display_errors', true);
ini_set('date.timezone', 'Asia/Shanghai');
define("_ROOT", dirname(__DIR__));
include '../kernel/Dispatcher.php';
(new Dispatcher())->run();
