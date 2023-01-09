<?php
if (!defined('BXSENDER_CRON')) {
    define('MODX_API_MODE', true);
    require_once dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/config.core.php';
    require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
    require_once MODX_CONNECTORS_PATH . 'index.php';
}

$modx->addPackage('bxsender', MODX_CORE_PATH . 'components/bxsender/model/');

/* @var bxSender $bxSender */
$bxSender = $modx->getService('bxsender', 'bxSender', MODX_CORE_PATH . 'components/bxsender/model/');

// Запускается только если метод запука рассылки установлен crontab
$method = $modx->getOption('bxsender_mailsender_method', null, '');
$send = ($method == 'crontab' or defined('BXSENDER_CRON')) ? true : false;
if ($send) {

    /* @var bxMailing $Mailing */
    $q = $modx->newQuery('bxMailing');
    $q->where(array(
        'active' => 1,
        'shipping_status' => 'process',
        'service' => 'bxsender',
        'start_mailing:<' => time(),
    ));
    $q->sortby('id', 'ASC');
    if ($Mailings = $modx->getCollection('bxMailing', $q)) {
        foreach ($Mailings as $Mailing) {
            $response = $Mailing->runProcessMailing($bxSender);
            if ($response !== true) {
                $modx->log(modX::LOG_LEVEL_ERROR, $response, '', __METHOD__, __FILE__, __LINE__);
            }
        }
    }
}