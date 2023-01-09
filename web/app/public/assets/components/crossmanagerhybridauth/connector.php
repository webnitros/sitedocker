<?php
if (file_exists(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php')) {
    /** @noinspection PhpIncludeInspection */
    require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
} else {
    require_once dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/config.core.php';
}
/** @noinspection PhpIncludeInspection */
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
/** @noinspection PhpIncludeInspection */
require_once MODX_CONNECTORS_PATH . 'index.php';
/** @var crossManagerHybridauth $crossManagerHybridauth */
$crossManagerHybridauth = $modx->getService('crossManagerHybridauth', 'crossManagerHybridauth', MODX_CORE_PATH . 'components/crossmanagerhybridauth/model/');
$modx->lexicon->load('crossmanagerhybridauth:default');

// handle request
$corePath = $modx->getOption('crossmanagerhybridauth_core_path', null, $modx->getOption('core_path') . 'components/crossmanagerhybridauth/');
$path = $modx->getOption('processorsPath', $crossManagerHybridauth->config, $corePath . 'processors/');
$modx->getRequest();

/** @var modConnectorRequest $request */
$request = $modx->request;
$request->handleRequest([
    'processors_path' => $path,
    'location' => '',
]);