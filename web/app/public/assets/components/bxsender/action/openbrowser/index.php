<?php
define('MODX_API_MODE', true);
require_once dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . '/index.php';

if (!empty($_GET['mailing_id']) and $modx->getAuthenticatedUser('mgr')) {
    if (!$modx->getAuthenticatedUser('mgr')) {
        die('Access is denied');
    }
    $mailing_id = (int)$_GET['mailing_id'];
    if ($Mailing = $modx->getObject('bxMailing', $mailing_id)) {
        /* @var bxQueue $bxQueue */
        $bxQueue = $modx->newObject('bxQueue');
        $bxQueue->set('mailing_id', $Mailing->get('id'));
        $bxQueue->showTemplate = true;
        $bxQueue->set('testing', true);
        echo $bxQueue->content();
        exit();
    } else {
        die('Mailing could not found');
    }
}

/* @var bxSender $bxSender */
$bxSender = $modx->getService('bxsender', 'bxSender', MODX_CORE_PATH . 'components/bxsender/model/');
if ($data = $bxSender->getHashData()) {
    /* @var bxQueue $Queue */
    if ($Queue = $modx->getObject('bxQueue', $data['queue_id'])) {
        echo $Queue->get('email_body');
    }
}
