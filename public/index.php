<?php

session_start();
define('ROOT_DIR', dirname(__DIR__));
define('VIEW_DIR', ROOT_DIR . '/resources/views');

require(ROOT_DIR.'/vendor/autoload.php');
require_once(ROOT_DIR.'/app/helpers.php');

use App\Routing\Router;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(ROOT_DIR);
$dotenv->load();

require_once(ROOT_DIR.'/routes/web.php');

Router::start();
