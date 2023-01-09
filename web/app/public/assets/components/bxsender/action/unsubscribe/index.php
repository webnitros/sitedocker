<?php
define('MODX_API_MODE', true);
require_once dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . '/index.php';

/* @var bxSender $bxSender */
$bxSender = $modx->getService('bxsender', 'bxSender', MODX_CORE_PATH . 'components/bxsender/model/');

// Если установлена страница то автоматически редирект
$page_id = $modx->getOption('bxsender_page_unsubscribe', null, false);
if (!empty($page_id)) {
    $url = $modx->makeUrl($page_id, '', $_GET, 'full');
    return $modx->sendRedirect($url);
} else {
    $outer = $modx->runSnippet('bxUnSubscribeFast');
    echo '<html><head><title>Отписка от рассылки</title><link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"><link rel="stylesheet" href="' . $bxSender->config['assetsUrl'] . 'css/web/main.css" type="text/css"></head><body class="bxsender_body"><div class="bxsender_form"><h1>Вы успешно отписаны</h1>' . $outer . '</div></body></html>';
}
