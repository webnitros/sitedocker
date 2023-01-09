<?php
define('BASE_DIR', dirname(__FILE__) . '/');
require_once BASE_DIR . 'vendor/autoload.php';
\App\Helpers\Env::loadFile(dirname(BASE_DIR, 1) . '/.env');

if (!defined('MODX_CONFIG_KEY')) {
    define('MODX_CONFIG_KEY', 'config');
}

if (!defined('MODX_CORE_PATH')) {
    define('MODX_CORE_PATH', BASE_DIR . 'public/core/');
}
