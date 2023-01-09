<?php
define('MODX_API_MODE', true);
require_once dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . '/index.php';

$hash_link = isset($_REQUEST['hash_link']) ? (string)$_REQUEST['hash_link'] : '';
if (empty($hash_link)) {
    $modx->log(modX::LOG_LEVEL_ERROR, "Error update empty hash: {$hash}", '');
    echo 'Error load hash';
} else {

    $hash_link = preg_replace('/[^a-zA-Z0-9]/', '', $hash_link);
    if (strlen($hash_link) == 12) {

        /* @var bxSender $bxSender */
        $bxSender = $modx->getService('bxsender', 'bxSender', MODX_CORE_PATH . 'components/bxsender/model/');


        if ($data = $bxSender->getHashData()) {

            // Events Before
            $bxSender->invokeEvent('bxOnBeforeActionClicks', array(
                'data' => $data
            ));

            // Фиксируем открытие страницы
            if ($data['queue_id']) {
                /* @var bxStatClicks $Clicks */
                if (!$Clicks = $modx->getObject('bxStatClicks', array('queue_id' => $data['queue_id']))) {
                    $Clicks = $modx->newObject('bxStatClicks');
                    $Clicks->fromArray($data);
                }
                $Clicks->countClicksSave();

                
                // Events After
                $bxSender->invokeEvent('bxOnAfterActionClicks', array(
                    'data' => $data,
                    'bxStatClicks' => $Clicks
                ));
            }
        }

        if ($Url = $modx->getObject('bxUrl', array('hash' => $hash_link))) {

            // Events After
            $bxSender->invokeEvent('bxOnBeforeActionClicksRedirect', array(
                'data' => $data,
                'bxUrl' => $Url
            ));

            // Если ссылка найдена то делаем перенаправление
            $Url->redirect();
            exit();
        }
    }
}
