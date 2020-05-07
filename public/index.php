<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

define('ROOT', dirname(dirname(__FILE__)));
define('DS', DIRECTORY_SEPARATOR);

include ROOT . DS . 'autoload.php';

\base\Application::getInstance()->run(ROOT, 'config/main.php');