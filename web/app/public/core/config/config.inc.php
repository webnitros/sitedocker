<?php
/**
 *  MODX Configuration file
 */
//----------------------------------------------------------------------------------
$database_type = 'mysql';
$database_server = getenv('MYSQL_HOST');
$database_user = getenv('MYSQL_USER');
$database_password = getenv('MYSQL_PASSWORD');
$database_connection_charset = 'utf8mb4';
$dbase = getenv('MYSQL_DATABASE'); //поменять на свою
$table_prefix = 'modx_';//обязательно поменять на свой: modx_
$database_dsn = "$database_type:host=$database_server;dbname=$dbase;charset=$database_connection_charset";

$config_options = [
];
$driver_options = [
];
//-------------------------------------------------------------------------------------------------------------
$lastInstallTime = 1624285461;

$site_id = 'modx60d0a1156870e4.58030107';
$site_sessionname = 'SN60d0a0f6ce591';
$https_port = '443';
$uuid = 'c2ddcdff-5dcc-4c37-a179-60c435f21a27';

$basePath = dirname(dirname(__DIR__));
if (!defined('MODX_VENDOR_PATH')) {
    $modx_vendor_path = dirname($basePath) . '/vendor';
    define('MODX_VENDOR_PATH', $modx_vendor_path);
}
if (!defined('MODX_SRC_PATH')) {
    $modx_src_path = $basePath . '/src/';
    define('MODX_SRC_PATH', $modx_src_path);
}
if (!defined('MODX_CORE_PATH')) {
    $modx_core_path = $basePath . '/core/';
    define('MODX_CORE_PATH', $modx_core_path);
}
if (!defined('MODX_PROCESSORS_PATH')) {
    $modx_processors_path = $basePath . '/core/model/modx/processors/';
    define('MODX_PROCESSORS_PATH', $modx_processors_path);
}
if (!defined('MODX_CONNECTORS_PATH')) {
    $modx_connectors_path = $basePath . '/connectors/';
    $modx_connectors_url = '/connectors/';
    define('MODX_CONNECTORS_PATH', $modx_connectors_path);
    define('MODX_CONNECTORS_URL', $modx_connectors_url);
}
if (!defined('MODX_MANAGER_PATH')) {
    $modx_manager_path = $basePath . '/manager/';
    $modx_manager_url = '/manager/';
    define('MODX_MANAGER_PATH', $modx_manager_path);
    define('MODX_MANAGER_URL', $modx_manager_url);
}
if (!defined('MODX_BASE_PATH')) {
    $modx_base_path = $basePath . '/';
    $modx_base_url = '/';
    define('MODX_BASE_PATH', $modx_base_path);
    define('MODX_BASE_URL', $modx_base_url);
}
if (defined('PHP_SAPI') && (PHP_SAPI == "cli" || PHP_SAPI == "embed")) {
    $isSecureRequest = FALSE;
} else {
    $isSecureRequest = ((isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') || $_SERVER['SERVER_PORT'] == $https_port);
}
if (!defined('MODX_URL_SCHEME')) {
    $url_scheme = $isSecureRequest ? 'https://' : 'http://';
    define('MODX_URL_SCHEME', $url_scheme);
}
if (!defined('MODX_HTTP_HOST')) {
    if (defined('PHP_SAPI') && (PHP_SAPI == "cli" || PHP_SAPI == "embed")) {
        $http_host = 'public';
        define('MODX_HTTP_HOST', $http_host);
    } else {
        $http_host = array_key_exists('HTTP_HOST', $_SERVER) ? htmlspecialchars($_SERVER['HTTP_HOST'], ENT_QUOTES) : 'public';
        if ($_SERVER['SERVER_PORT'] != 80) {
            $http_host = str_replace(':' . $_SERVER['SERVER_PORT'], '', $http_host); // remove port from HTTP_HOST
        }
        $http_host .= ($_SERVER['SERVER_PORT'] == 80 || $isSecureRequest) ? '' : ':' . $_SERVER['SERVER_PORT'];
        define('MODX_HTTP_HOST', $http_host);
    }
}
if (!defined('MODX_SITE_URL')) {
    $site_url = $url_scheme . $http_host . MODX_BASE_URL;
    define('MODX_SITE_URL', $site_url);
}
if (!defined('MODX_ASSETS_PATH')) {
    $modx_assets_path = $basePath . '/assets/';
    $modx_assets_url = '/assets/';
    define('MODX_ASSETS_PATH', $modx_assets_path);
    define('MODX_ASSETS_URL', $modx_assets_url);
}
if (!defined('MODX_LOG_LEVEL_FATAL')) {
    define('MODX_LOG_LEVEL_FATAL', 0);
    define('MODX_LOG_LEVEL_ERROR', 1);
    define('MODX_LOG_LEVEL_WARN', 2);
    define('MODX_LOG_LEVEL_INFO', 3);
    define('MODX_LOG_LEVEL_DEBUG', 4);
}
